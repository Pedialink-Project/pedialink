<?php

namespace App\Controllers\Admin;
use App\Models\ParentM;
use App\Models\User;
use App\Services\Admin\AdminUserService;
use App\Services\Admin\ParentApprovalService;
use App\Services\Admin\UserOverviewService;
use App\Services\RegisterStaffService;
use Library\Framework\Http\Request;
use Library\Framework\Http\Response;

class UserController
{
    private UserOverviewService $userOverviewService;
    private AdminUserService $adminUserService;
    private RegisterStaffService $registerStaffService;
    private ParentApprovalService $parentApprovalService;

    public function __construct()
    {
        $this->userOverviewService = new UserOverviewService();
        $this->adminUserService = new AdminUserService();
        $this->registerStaffService = new RegisterStaffService();
        $this->parentApprovalService = new ParentApprovalService();
    }

    public function overview(Request $request)
    {
        $search = $request->input('search');
        $filters = $request->input('filters');
        [$users, $links] = $this->userOverviewService->getUserDetails(
            $search,
            $filters
        );
        return view('admin/user/overview', [
            'users' => $users,
            'links' => $links,
        ]);
    }

    public function registerStaff(Request $request)
    {
        $data = [
            'email' => $request->input('email') ?? '',
            'role' => $request->input('role') ?? '',
            'message' => $request->input('message') ?? '',
        ];

        $errors = $this->registerStaffService->validateStaffData(
            $data['email'],
            $data['role'],
            $data['message']
        );

        if (count($errors) !== 0) {
            return redirect(route('admin.user.overview'))
                ->withErrors($errors)
                ->withInput($data)
                ->with('create', true);
        }

        $this->registerStaffService->saveStaff(
            $data['email'],
            $data['role'],
            $data['message'],
        );

        return redirect(route('admin.user.overview'))
            ->withMessage(
                'Staff created successfully',
                'Account created',
                'success'
            );
    }

    public function parentAccountApproval()
    {
        [$parents, $links] = $this->parentApprovalService->getPendingParentDetails();
        return view('admin/user/parent', [
            'parents' => $parents,
            'links' => $links,
        ]);
    }

    public function parentDocumentDownload(Request $request, int $id, string $type)
    {
        /**
         * @var ParentM
         */
        $parent = ParentM::find($id);
        $link = "";

        if ($parent) {
            switch (strtolower($type)) {
                case "birth":
                    $link = storage()
                        ->temporaryUrl('local', $parent->birth_certificate);
                    break;
                case "marriage":
                    $link = storage()
                        ->temporaryUrl('local', $parent->marriage_certificate);
                    break;
                case "nic":
                    $link = storage()
                        ->temporaryUrl('local', $parent->nic_copy);
                    break;
            }
        }

        return redirect($link);
    }

    public function parentApprove(Request $request, int $id)
    {
        $parent = ParentM::find($id);

        if ($parent) {
            $parent->verified = 1;
            $parent->save();

            return redirect(route('admin.user.parent'))
                ->withMessage(
                    'Parent has been approved successfully',
                    'Success',
                    'success'
                );
        }

        return redirect(route('admin.user.parent'))
            ->withMessage(
                'Failed to approve parent',
                'Failure',
                'error'
            );
    }

    public function parentDeny(Request $request, int $id)
    {
        $parent = ParentM::find($id);

        if ($parent) {
            $parentId = $parent->id;
            $parent->delete();
            
            $user = User::find($parentId);
            if ($user) {
                $user->delete();
            }

            return redirect(route('admin.user.parent'))
                ->withMessage(
                    'Parent account has been denied access successfully',
                    'Success',
                    'success'
                );
        }

        return redirect(route('admin.user.parent'))
            ->withMessage(
                'Failed to deny parent access',
                'Failure',
                'error'
            );
    }

    public function createAdmin(Request $request)
    {
        $name = $request->input("name");
        $email = $request->input("email");
        $type = $request->input("type");

        $errors = $this->adminUserService
            ->validateAdminUser($name, $email, $type);

        if (count($errors) !== 0) {
            return redirect(route("admin.user.admin"))
                ->withInput([
                    "name" => $name,
                    "email" => $email
                ])
                ->withErrors($errors)
                ->with("create", true);
        }

        $this->adminUserService->createAdminUser($name, $email, $type);

        return redirect(route("admin.user.admin"))
            ->withMessage(
                "Admin user was successfully created",
                "Success",
                "success",
            );
    }

    public function editAdmin(Request $request, int $id)
    {
        $name = $request->input("e_name");
        $email = $request->input("e_email");
        $type = $request->input("e_type");

        $errors = $this->adminUserService
            ->validateAdminUserChanges($id, $name, $email, $type);

        if (count($errors) !== 0) {
            return redirect(route("admin.user.admin"))
                ->withInput([
                    "e_name" => $name,
                    "e_email" => $email
                ])
                ->withErrors($errors)
                ->with("edit", $id);
        }

        $this->adminUserService->editAdminUser($id, $name, $email, $type);

        return redirect(route("admin.user.admin"))
            ->withMessage(
                "Changes were successfully saved",
                "Changes Saved",
                "success",
            );
    }

    public function deleteAdmin(Request $request, int $id)
    {
        $error = $this->adminUserService->validateDeleteAdminUser($id);

        if ($error !== NULL) {
            return redirect(route("admin.user.admin"))
                ->with("delete", false)
                ->withMessage(
                    $error,
                    "Failed",
                    "error",
                );
        }

        $this->adminUserService->deleteAdminUser($id);
        
        return redirect(route("admin.user.admin"))
            ->with("delete", true)
            ->withMessage(
                "Admin with ID: A-$id was successfully deleted",
                "Deleted Successfully",
                "success",
            );
    }

    public function admin(Request $request)
    {
        $search = $request->input('search');
        $filters = $request->input('filters');
        $admins = $this->adminUserService->getAdminDetails(
            $search,
            $filters
        );

        return view('admin/user/admin', [
            "admins" => $admins,
        ]);
    }
}