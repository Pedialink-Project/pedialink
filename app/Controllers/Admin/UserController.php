<?php

namespace App\Controllers\Admin;
use App\Services\Admin\AdminUserService;
use App\Services\Admin\UserOverviewService;
use Library\Framework\Http\Request;

class UserController
{
    private UserOverviewService $userOverviewService;
    private AdminUserService $adminUserService;

    public function __construct()
    {
        $this->userOverviewService = new UserOverviewService();
        $this->adminUserService = new AdminUserService();
    }

    public function overview()
    {
        $users = $this->userOverviewService->getUserDetails();
        return view('admin/user/overview', [
            'users' => $users,
        ]);
    }

    public function parentAccountApproval()
    {
        return view('admin/user/parent');
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

    public function admin()
    {
        $admins = $this->adminUserService->getAdminDetails();

        return view('admin/user/admin', [
            "admins" => $admins,
        ]);
    }
}