<?php

namespace App\Controllers;

use App\Models\Area;
use App\Models\ParentM;
use App\Models\User;
use App\Services\AuthService;
use Library\Framework\Http\Request;
use Library\Framework\Http\Response;
use Library\Framework\Session\SessionManager;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
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

        // NOTE: If possible refactor this to AuthService class if you have the time!
        // Otherwise Focus on frontend work exclusively until interim!
        if (count($errors) === 0) {
            $data = array_merge($data, session()->get("register_form"));

            $user = new User();
            $user->name = $data["firstName"] . " " . $data["lastName"];
            $user->email = $data["email"];
            $user->password_hash = $data["passwordHash"];
            $user->role = "parent";
            $userId = $user->save();

            $parent = new ParentM();
            $parent->id = $userId;
            $parent->type = $data["type"];
            $parent->address = $data["address"];
            $parent->nic = $data["nic"];
            $parent->area_id = (int)$data["division"];
            $parent->save();

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

    public function forgotPassword(Request $request)
    {
        return view('auth/forgot-password');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect(route('home'));
    }
}