<?php

namespace App\Controllers;

use Library\Framework\Http\Request;

class VerifyController
{
    private function preventEmailVerifyViewing()
    {
        $user = auth()->user();

        if ($user) {
            if ($user->email_verified) {
                return true;
            }
        }

        return false;
    }

    private function preventParentVerifyViewing()
    {
        $user = auth()->user();

        if ($user && $user->isParent()) {
            $parent = $user->getRole();

            if ($parent->verified) {
                return true;
            }
        }

        return false;
    }

    public function emailUnverified(Request $request)
    {
        if ($this->preventEmailVerifyViewing()) {
            return view("error/404");
        }

        $blocked = $request->query("blocked") ?? false;

        return view("auth/email-unverified", [
            "blocked" => $blocked,
        ]);
    }

    public function parentUnverified(Request $request)
    {
        if (!$this->preventEmailVerifyViewing() || $this->preventParentVerifyViewing()) {
            return view("error/404");
        }

        $parent = auth()->user()?->getRole();

        $submitted = $parent?->birth_certificate &&
            $parent?->marriage_certificate &&
            $parent?->nic_copy;

        return view("auth/parent-unverified", [
            "submitted" => $submitted,
        ]);
    }
}