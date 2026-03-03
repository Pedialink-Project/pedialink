<?php

namespace App\Services\Admin;

use App\Models\Child;
use App\Models\ChildAccessRequest;
use App\Models\ChildMisc;
use App\Models\Doctor;
use App\Models\EventRegistrations;
use App\Models\Events;
use App\Models\ParentM;
use App\Models\PublicHealthMidwife;
use App\Services\EventService;
use Library\Framework\Database\QueryBuilder;

class DashboardService
{
    private EventService $eventService;

    public function __construct()
    {
        $this->eventService = new EventService();
    }

    public function getTotalChildrenCount()
    {
        $children = Child::all();

        return count($children);
    }

    public function getActivePhmCount()
    {
        $phm = PublicHealthMidwife::all();

        return count($phm);
    }

    public function getTotalParentsCount()
    {
        $parents = ParentM::all();

        return count($parents);
    }

    public function getTotalAccessRequestsCount()
    {
        $requests = ChildAccessRequest::query()
            ->where("accepted", "=", 0)
            ->get();

        return count($requests);
    }

    public function getTotalLinkageRequestsCount()
    {
        $requests = ChildMisc::query()
            ->where("accepted", "=", 0)
            ->get();

        return count($requests);
    }

    public function getActiveDoctorsCount()
    {
        $doctors = Doctor::all();

        return count($doctors);
    }

    public function getVaccinationChartData()
    {
        $year = (int)date('Y');

        // Safe: we cast $year to int above so direct interpolation here is safe.
        // SQL: group by month (1..12) and sum CASEs for statuses.
        $sql = "
        SELECT
            EXTRACT(MONTH FROM scheduled_date)::int AS month,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END)::int AS scheduled,
            SUM(CASE WHEN status = 'complete' THEN 1 ELSE 0 END)::int AS completed
        FROM vaccination_reminders
        WHERE EXTRACT(YEAR FROM scheduled_date)::int = {$year}
        GROUP BY month
        ORDER BY month
        ";

        // Execute raw SQL. QueryBuilder::raw should return an array of rows.
        // If your QueryBuilder returns objects instead of arrays, adapt accordingly.
        $rows = QueryBuilder::rawGet($sql);

        // Initialize 12-month buckets (index 0 -> January)
        $scheduled = array_fill(0, 12, 0);
        $completed = array_fill(0, 12, 0);

        if (!empty($rows) && is_array($rows)) {
            foreach ($rows as $r) {
                // $r may be either assoc array or object depending on QueryBuilder impl
                $month = null;
                $sched = 0;
                $comp = 0;

                if (is_object($r)) {
                    $month = isset($r->month) ? (int)$r->month : null;
                    $sched = isset($r->scheduled) ? (int)$r->scheduled : 0;
                    $comp = isset($r->completed) ? (int)$r->completed : 0;
                } elseif (is_array($r)) {
                    $month = isset($r['month']) ? (int)$r['month'] : null;
                    $sched = isset($r['scheduled']) ? (int)$r['scheduled'] : 0;
                    $comp = isset($r['completed']) ? (int)$r['completed'] : 0;
                }

                if ($month !== null && $month >= 1 && $month <= 12) {
                    $idx = $month - 1; // convert to 0-based index
                    $scheduled[$idx] = $sched;
                    $completed[$idx] = $comp;
                }
            }
        }

        return [
            'scheduled' => $scheduled,
            'completed' => $completed,
        ];
    }

    public function getParentApprovalRequests()
    {
        $requests = ParentM::query()
            ->where("verified", "=", 0)
            ->get();

        $requests = array_slice($requests, 0, 5);
        $resource = [];
        foreach ($requests as $req) {
            $user = $req->getUser();
            $resource[] = [
                "id" => $req->id,
                "name" => $user->name,
                "type" => $req->type
            ];
        }

        return $resource;
    }

    public function getEventsData()
    {
        $events = Events::query()
            ->where("visible", "=", 1)
            ->limit(3)
            ->get();

        $resource = [];
        foreach ($events as $event) {
            $eventRegistration = EventRegistrations::query()
                ->where("event_id", "=", $event->id)
                ->get();
            $resource[] = [
                "id" => $event->id,
                "title" => $event->title,
                "description" => $event->description,
                "date" => $event->event_date,
                "start_time" => $event->start_time,
                "count" => count($eventRegistration),
                "location" => $event->event_location,
                "status" => $this->eventService->getEventStatus($event->id)
            ];
        }

        return $resource;
    }

    public function getWeeklyAppointmentsData()
    {
        // Get the current week's Monday and Friday
        $today = new \DateTimeImmutable('today');
        $dayOfWeek = (int)$today->format('N'); // 1 = Monday, 7 = Sunday
        $monday = $today->modify('-' . ($dayOfWeek - 1) . ' days')->format('Y-m-d');
        $friday = $today->modify('+' . (5 - $dayOfWeek) . ' days')->format('Y-m-d');

        // SQL: join appointments with slots, group by day of week (1=Mon..5=Fri)
        // Count attended as completed, no-show and cancelled as cancelled
        $sql = "
        SELECT
            EXTRACT(DOW FROM s.slot_date)::int AS dow,
            SUM(CASE WHEN a.status = 'attended' THEN 1 ELSE 0 END)::int AS completed,
            SUM(CASE WHEN a.status IN ('no-show', 'cancelled') THEN 1 ELSE 0 END)::int AS cancelled,
            COUNT(*)::int AS booked
        FROM appointments a
        JOIN appointment_slots s ON a.slot_id = s.id
        WHERE s.slot_date >= :monday AND s.slot_date <= :friday
        GROUP BY dow
        ORDER BY dow
        ";

        $rows = QueryBuilder::rawGet($sql, [':monday' => $monday, ':friday' => $friday]);

        // Initialize 5-day buckets (index 0 -> Monday, index 4 -> Friday)
        $booked = array_fill(0, 5, 0);
        $completed = array_fill(0, 5, 0);
        $cancelled = array_fill(0, 5, 0);

        if (!empty($rows) && is_array($rows)) {
            foreach ($rows as $r) {
                $dow = null;
                $comp = 0;
                $canc = 0;
                $book = 0;

                if (is_array($r)) {
                    // PostgreSQL DOW: 0=Sunday, 1=Monday, ..., 5=Friday, 6=Saturday
                    $dow = isset($r['dow']) ? (int)$r['dow'] : null;
                    $comp = isset($r['completed']) ? (int)$r['completed'] : 0;
                    $canc = isset($r['cancelled']) ? (int)$r['cancelled'] : 0;
                    $book = isset($r['booked']) ? (int)$r['booked'] : 0;
                }

                // Map DOW 1-5 (Mon-Fri) to array index 0-4
                if ($dow !== null && $dow >= 1 && $dow <= 5) {
                    $idx = $dow - 1;
                    $booked[$idx] = $book;
                    $completed[$idx] = $comp;
                    $cancelled[$idx] = $canc;
                }
            }
        }

        return [
            'booked' => $booked,
            'completed' => $completed,
            'cancelled' => $cancelled,
        ];
    }
}