<?php

namespace App\Services;

use App\Helpers\SignedToken;
use App\Helpers\Validator;
use App\Models\User;
use App\Rules\PasswordRule;

class ForgotPasswordService
{
    use PasswordRule;

    private function validateEmail(string $email)
    {
        $error = null;

        if (!Validator::validateFieldExistence($email)) {
            $error = "Email field cannot be empty";
            return $error;
        }

        $user = User::query()->where('email', '=', $email)->first();
        if (!$user) {
            $error = "This email is not registered with our system";
            return $error;
        }

        return $error;
    }

    public function validateData(string $email)
    {
        $errors = [];

        $emailError = $this->validateEmail($email);
        if ($emailError) {
            $errors['email'] = $emailError;
        }

        return $errors;
    }

    public function sendResetLink(string $email)
    {
        /**
         * @var User
         */
        $user = User::query()->where("email", "=", $email)->first();

        if ($user) {
            $token = SignedToken::createSignedVerificationToken(
                $user,
                config('app.key')
            );

            $signedUrl = rtrim(config('app.url'), '/') .
                route('reset.password', [], ['token' => $token]);

            try {
                mailer()->sendTemplate(
                    $user->email,
                    'forgot-password',
                    [
                        'username' => $user->name,
                        'forgot_link' => $signedUrl,
                    ],
                    'Reset your password'
                );
            } catch (\Exception $e) {
                return false;
            }
        }

        return true;
    }

    public function validateResetData(string $password, string $confirm_password)
    {
        $errors = [];

        $passwordError = $this->validatePassword($password, $confirm_password);
        if ($passwordError) {
            $errors['password'] = $passwordError;
        }

        return $errors;
    }

    public function resetPassword(User $user, string $password)
    {
        $userId = $user->id;
        $user->password_hash = password_hash($password, PASSWORD_DEFAULT);
        $user->save();

        return User::find($userId);
    }
}