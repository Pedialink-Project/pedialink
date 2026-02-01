<?php

namespace App\Services\Admin;

use App\Models\Child;
use App\Models\ChildAccessRequest;
use App\Models\Staff;

class ChildAccessRequestsService
{
    public function getAccessRequestsData(): array
    {
        $accessRequests = ChildAccessRequest::query()
            ->orderBy("id", "ASC")
            ->paginate(6)
            ->toArray();

        $resource = [];

        foreach ($accessRequests["items"] as $accessRequest) {
            /** @var Staff */
            $staff = $accessRequest->getStaff();
            
            /** @var Child */
            $child = $accessRequest->getChild();
            $resource[] = [
                "id" => $accessRequest->id,
                "staff" => [
                    "id" => $staff->id,
                    "name" => $staff->getUser()->name,
                    "role" => $staff->getUser()->role,
                    "nic" => $staff->nic,
                ],
                "child" => [
                    "id" => $child->id,
                    "name" => $child->name,
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