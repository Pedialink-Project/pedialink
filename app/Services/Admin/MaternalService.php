<?php

namespace App\Services\Admin;

use App\Models\MaternalAccessRequest;
use App\Models\ParentM;
use App\Models\Staff;
use App\Models\User;

class MaternalService
{
    public function getMaternalData(string $search)
    {
        $maternal = ParentM::query()
            ->where("type", "=", "mother")
            // ->where("name", "ILIKE", "")
            ->orderBy("id", "ASC")
            ->paginate(10)
            ->toArray();

        $resource = [];

        foreach ($maternal['items'] as $mother) {
            $user = User::find($mother->id);

            $resource[] = [
                "id" => $mother->id,
                "name" => $user->name,
                "nic" => $mother->nic,
                "age" => calculateAge($mother->date_of_birth),
                "division" => $mother->getArea()->code,
            ];
        }

        $links = array_diff_key($maternal, ['items' => true]);

        return [$resource, $links];
    }

    public function getAccessRequestData()
    {
        $accessRequests = MaternalAccessRequest::query()
            ->where("accepted", "=", 0)
            ->orderBy("id", "ASC")
            ->paginate(6)
            ->toArray();

        $resource = [];

        foreach ($accessRequests["items"] as $accessRequest) {
            /** @var Staff */
            $staff = $accessRequest->getStaff();
            
            /** @var ParentM */
            $maternal = $accessRequest->getMaternal();

            $maternalUser = User::find($maternal->id);
            $resource[] = [
                "id" => $accessRequest->id,
                "staff" => [
                    "id" => $staff->id,
                    "name" => $staff->getUser()->name,
                    "role" => $staff->getUser()->role,
                    "nic" => $staff->nic,
                ],
                "maternal" => [
                    "id" => $maternal->id,
                    "name" => $maternalUser->name,
                ],
                "reason_title" => $accessRequest->reason_title,
                "reason_description" => $accessRequest->reason_description,
                "created_at" => $accessRequest->created_at
            ];
        }

        $links = array_diff_key($accessRequests, ['items' => true]);

        return [$resource, $links];
    }
}