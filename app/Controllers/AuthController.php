<?php

namespace App\Controllers;

use App\Helpers\SignedToken;
use App\Models\Area;
use App\Services\AuthService;
use App\Services\EmailVerificationService;
use App\Services\ForgotPasswordService;
use App\Services\RegisterStaffService;
use Library\Framework\Http\Request;

class AuthController
{
    private AuthService $authService;
    private EmailVerificationService $emailVerificationService;
    private RegisterStaffService $registerStaffService;
    private ForgotPasswordService $forgotPasswordService;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->emailVerificationService = new EmailVerificationService();
        $this->registerStaffService = new RegisterStaffService();
        $this->forgotPasswordService = new ForgotPasswordService();
    }

    public function parentRegisterInitial(Request $request)
    {
        // this if condition resets previous password hash if user goes back to initial form
        if ($registerFormSession = session()->get("register_form", null)) {
            $registerFormSession["passwordHash"] = null;
            session()->set("register_form", $registerFormSession);
        }

        return view('auth/parent-register');
    }

    public function parentVerifyInitial(Request $request)
    {
        $data = [
            "firstName" => $request->input("firstName"),
            "lastName" => $request->input("lastName"),
            "email" => $request->input("email"),
        ];

        $errors = $this->authService->validateInitialForm(array_merge($data, [
            "password" => $request->input("password"),
            "confirmPassword" => $request->input("confirmPassword"),
        ]));

        if (count($errors) === 0) {
            session()->set("register_form", array_merge($data, [
                "passwordHash" => password_hash(
                    $request->input("password"), PASSWORD_DEFAULT
                ),
            ]));
            return redirect(route("parent.register.final")); // redirect user to next form on success
        }

        // redirect user to current form with errors on failure
        return redirect(route("parent.register"))
            ->withInput($data)
            ->withErrors($errors);
    }

    public function parentRegisterFinal(Request $request)
    {
        $registerSessionForm = session()->get("register_form", null);

        $areas = Area::all();

        if ($registerSessionForm && $registerSessionForm["passwordHash"] !== null) {
            return view('auth/parent-register', [
                "final" => true,
                "areas" => $areas,
            ]);
        }

        return redirect(route("parent.register"));
    }

    public function parentVerifyFinal(Request $request)
    {
        $data = [
            "type" => htmlspecialchars(trim($request->input("type"))),
            "nic" => htmlspecialchars(trim($request->input("nic"))),
            "address" => htmlspecialchars(trim($request->input("address"))),
            "division" => htmlspecialchars(trim($request->input("division"))),
        ];

        $errors = $this->authService->validateFinalForm($data);

        if (count($errors) === 0) {
            $data = array_merge($data, session()->get("register_form"));

            $user = $this->authService->createParentAccount($data);

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

            auth()->login($user);

            return redirect(route("parent.dashboard"));
        }

        return redirect(route("parent.register.final"))
            ->withInput($data)
            ->withErrors($errors);
    }

    public function parentLogin(Request $request)
    {
        return view('auth/parent-login');
    }

    public function loginAsParent(Request $request)
    {
        $email = htmlspecialchars(trim($request->input("email"))) ?? '';
        $password = htmlspecialchars($request->input("password")) ?? '';

        if (auth()->attempt($email, $password, "parent")) {
            return redirect(route("parent.dashboard"));
        }

        return redirect(route("parent.login"))
            ->withInput([
                "email" => $email,
            ])
            ->withErrors([
                "email" => "Invalid username or password",
            ]);
    }

    public function staffLogin(Request $request)
    {
        return view('auth/staff-login');
    }

    public function loginAsStaff(Request $request)
    {
        $email = htmlspecialchars(trim($request->input("email"))) ?? '';
        $password = htmlspecialchars($request->input("password")) ?? '';

        if (auth()->attempt($email, $password, "parent", "!=")) {
            $user = auth()->user();

            if ($user->isPublicHealthMidwife()) {
                return redirect(route("phm.dashboard"));
            }

            if ($user->isDoctor()) {
                return redirect(route("doctor.dashboard"));
            }

            if ($user->isAdmin()) {
                return redirect(route("admin.dashboard"));
            }
        }

        return redirect(route("staff.login"))
            ->withInput([
                "email" => $email,
            ])
            ->withErrors([
                "email" => "Invalid username or password",
            ]);
    }

    public function registerStaffView(Request $request)
    {
        $token = $request->input('token') ?? '';

        [$user, $verified] = SignedToken::verifySignedToken($token, config('app.key'));

        if (!$verified || $user->email_verified === true) {
            return redirect(route('home'))
                ->withMessage('Invalid token', 'Failure', 'error');
        }

        $areas = Area::all();

        return view('auth/staff-register', [
            'token' => $token,
            'email' => $user->email,
            'role' => $user->role,
            'areas' => $areas,
        ]);
    }

    public function registerStaff(Request $request)
    {
        $token = $request->input('token') ?? '';

        [$user, $verified] = SignedToken::verifySignedToken($token, config('app.key'));

        if (!$verified || $user->email_verified === true) {
            return redirect(route('staff.register', [], ['token' => $token]))
                ->withMessage('Invalid token', 'Failure', 'error');
        }

        $data = [
            'name' => $request->input('name') ?? '',
            'nic' => $request->input('nic') ?? '',
            'license_no' => $request->input('license_no') ?? '',
            'password' => $request->input('password') ?? '',
            'confirm_password' => $request->input('confirm_password') ?? '',
        ];

        if ($user->isPublicHealthMidwife()) {
            $data['division'] = $request->input('division') ?? '';
        }

        $errors = $this->registerStaffService->validateFinalStaffData(
            $user,
            $data['name'],
            $data['nic'],
            $data['license_no'],
            $data['password'],
            $data['confirm_password'],
            isset($data['division']) ? $data['division'] : null
        );

        if (count($errors) !== 0) {
            unset($data['password'], $data['confirm_password']);
            return redirect(route('staff.register', [], ['token' => $token]))
                ->withInput($data)
                ->withErrors($errors);
        }

        $newUser = $this->registerStaffService->saveStaffFinal(
            $user,
            $data['name'],
            $data['nic'],
            $data['license_no'],
            $data['password'],
            isset($data['division']) ? $data['division'] : null
        );

        auth()->login($newUser);

        $route = route('home');

        if ($user->role === "doctor") {
            $route = route('doctor.dashboard');
        } else if ($user->role === "phm") {
            $route = route('phm.dashboard');
        }

        return redirect($route);
    }

    public function forgotPassword(Request $request)
    {
        return view('auth/forgot-password');
    }

    public function sendResetPassword(Request $request)
    {
        $email = $request->input('email') ?? '';

        $errors = $this->forgotPasswordService->validateData($email);

        if (count($errors) !== 0) {
            return redirect(route('forgot.password'))
                ->withInput([
                    'email' => $email
                ])
                ->withErrors($errors);
        }

        $sent = $this->forgotPasswordService->sendResetLink($email);

        if (!$sent) {
            return redirect(route('forgot.password'))
                ->withInput([
                    'email' => $email
                ])
                ->withMessage('Failed to send email', 'Failure', 'error');
        }

        return redirect(route('home'))
            ->withMessage('Successfully sent reset password email', 'Sent email', 'success');
        
    }

    public function resetPasswordView(Request $request)
    {
        $token = $request->input('token') ?? '';

        [$user, $verified] = SignedToken::verifySignedToken($token, config('app.key'));

        if (!$verified) {
            return redirect(route('home'))
                ->withMessage('Invalid token', 'Failure', 'error');
        }

        return view('auth/reset-password', [
            'email' => $user->email,
            'token' => $token,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $token = $request->input('token') ?? '';

        [$user, $verified] = SignedToken::verifySignedToken($token, config('app.key'));

        if (!$verified) {
            return redirect(route('reset.password', [], ['token' => $token]))
                ->withMessage('Invalid token', 'Failure', 'error');
        }

        $password = $request->input('password') ?? '';
        $confirm_password = $request->input('confirm_password') ?? '';

        $errors = $this->forgotPasswordService->validateResetData(
            $password,
            $confirm_password
        );

        if (count($errors) !== 0) {
            return redirect(route('reset.password', [], ['token' => $token]))
                ->withErrors($errors);
        }

        $user = $this->forgotPasswordService
            ->resetPassword($user, $password);

        auth()->login($user);
        
        return redirect(route('home'))
            ->withMessage('Successfully reset your password', 'Success', 'success');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect(route('home'));
    }
}