<?php

namespace App\Services\Admin;

use App\Models\Child;
use App\Models\ChildAccessRequest;
use App\Models\ChildMisc;
use App\Models\Doctor;
use App\Models\ParentM;
use App\Models\PublicHealthMidwife;
use Library\Framework\Database\QueryBuilder;

class DashboardService
{
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
}