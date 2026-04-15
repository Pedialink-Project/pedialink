<?php

namespace App\Controllers\PublicHealthMidwife;

use App\Models\Child;
use App\Models\Vaccination;
use App\Models\VaccinationReminder;
use App\Services\NotificationService;
use App\Services\VaccinationSchedulerService;
use App\Services\VaccinationService;
use Library\Framework\Http\Request;

class VaccinationController
{
    private VaccinationService $vaccinationService;
    private VaccinationSchedulerService $vaccinationSchedulerService;
    private NotificationService $notificationService;

    public function __construct()
    {
        $this->vaccinationService = new VaccinationService();
        $this->vaccinationSchedulerService = new VaccinationSchedulerService();
        $this->notificationService = new NotificationService();
    }

    public function childVaccinationRecords(Request $request, int $id)
    {
        $search = $request->query("search", "");
        $filters = $request->query("filters", []);
        [$vaccinations, $links] = $this->vaccinationService->fetchVaccinationRecordsByChildId(
            $id,
            $search,
            $filters
        );

        $child = Child::find($id);
        return view("phm/vaccinationrecord", [
            "id" => $id,
            "name" => $child?->name ?? "Unknown Child",
            "vaccinations" => $vaccinations,
            "links" => $links,
        ]);
    }

    public function markAsCompleted(Request $request, int $id, int $recordId)
    {
        $child = Child::find($id);
        if (!$child) {
            return redirect(route("phm.child.vaccinations", ["id" => $id]))
                ->withMessage(
                    "An error occured while marking the vaccination record as completed. Please try again.",
                    "Error",
                    "error"
                );
        }

        $vaccinationReminder = VaccinationReminder::find($recordId);
        if (!$vaccinationReminder || $vaccinationReminder->child_id !== $id) {
            return redirect(route("phm.child.vaccinations", ["id" => $id]))
                ->withMessage(
                    "An error occured while marking the vaccination record as completed. Please try again.",
                    "Error",
                    "error"
                );
        }

        $vaccination = new Vaccination();
        $vaccination->child_id = $id;
        $vaccination->schedule_vaccine_id = $vaccinationReminder->schedule_vaccine_id;
        $vaccination->administered_at = (new \DateTime())->format("Y-m-d H:i:sP");
        $vaccination->recorded_at = (new \DateTime())->format("Y-m-d H:i:sP");
        $success = $vaccination->save();

        if ($success) {
            $this->vaccinationSchedulerService->refreshRemindersAfterVaccination($id);

            $parents = $child->getParents();
            $recipientIds = [];
            if ($parents) {
                foreach ($parents as $parent) {
                    $user = $parent->getUser();
                    if ($user) {
                        $recipientIds[] = (int)$user->id;
                    }
                }
            }

            if (!empty($recipientIds)) {
                $scheduleVaccine = $vaccinationReminder->getScheduleVaccine();
                $vaccine = $scheduleVaccine ? $scheduleVaccine->getVaccine() : null;
                $vaccineName = $vaccine ? $vaccine->name : 'a scheduled vaccine';

                $message = "Vaccination {$vaccineName} for {$child->name} was recorded as completed.";

                $this->notificationService->notifyMany(
                    $recipientIds,
                    "Vaccination completed",
                    $message,
                    "vaccination",
                    (int)$vaccination->id
                );
            }
        }

        return redirect(route("phm.child.vaccinations", ["id" => $id]))
            ->withMessage(
                "Vaccination record marked as completed successfully.",
                "Success",
                "success"
            );
    }
}