<?php

namespace App\Controllers;

use App\Services\EmailVerificationService;
use App\Services\Parent\VerificationService;
use Library\Framework\Http\Request;

class VerifyController
{
    private EmailVerificationService $emailVerificationService;
    private VerificationService $parentVerificationService;

    public function __construct()
    {
        $this->emailVerificationService = new EmailVerificationService();
        $this->parentVerificationService = new VerificationService();
    }

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

    public function verifyEmailSend(Request $request)
    {
        if ($this->preventEmailVerifyViewing()) {
            return view("error/404");
        }

        $user = auth()->user();
        $data = [
            'title' => 'Email sent',
            'message' => 'Your verification email has been successfully sent',
            'type' => 'success'
        ];

        try {
            if ($user) {
                $verifyLink = $this->emailVerificationService
                    ->createVerificationUrl(
                        $user, config('app.key')
                    );

                mailer()->sendTemplate(
                    $user->email,
                    'verify-email',
                    [
                        'username' => $user->name,
                        'verify_link' => $verifyLink
                    ],
                    'Verify your email',
                );
            }

        } catch (\Exception $e) {
            $data['title'] = 'Failure';
            $data['message'] = 'Failed to send verification email';
            $data['type'] = 'error';
        }

        return redirect(route('email.unverified', [],['blocked' => true]))
            ->withMessage($data['message'], $data['title'], $data['type']);
    }

    public function verifyEmail(Request $request)
    {
        if ($this->preventEmailVerifyViewing()) {
            return view("error/404");
        }

        $token = $request->query('token') ?? '';

        $user = auth()->user();

        if ($user) {
            $status = $this->emailVerificationService
                ->verifySignedTokenAndMarkVerified($token, config('app.key'));

            if ($status) {
                return redirect(route('home'))
                    ->withMessage('Email verified successfully', 'Email verified', 'success');
            }
        }

        return redirect(route('home'))
            ->withMessage('Failed to verify your email', 'Email not verified', 'error');
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

    public function submitParentDocuments(Request $request)
    {
        $birth_certificate = $request->file('birth_certificate') ?? [];
        $marriage_certificate = $request->file('marriage_certificate') ?? [];
        $nic_copy = $request->file('nic_copy') ?? [];

        $errors = $this->parentVerificationService->validateDocuments(
            $birth_certificate,
            $marriage_certificate,
            $nic_copy,
        );

        if (count($errors) !== 0) {
            return redirect(route('parent.unverified'))
                ->withErrors($errors);
                // ->withInput([
                //     'birth_certificate' => $birth_certificate,
                //     'marriage_certificate'
                // ]);
        }

        $this->parentVerificationService->uploadDocuments(
            $birth_certificate,
            $marriage_certificate,
            $nic_copy
        );

        return redirect(route('parent.unverified'));
    }
}