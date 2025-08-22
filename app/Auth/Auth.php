<?php

namespace App\Auth;

use App\Models\User;
use Library\Framework\Session\SessionManager;

class Auth
{
    private SessionManager $session;
    private ?User $user = null;
    private string $key = "auth_user_id";

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $id = $this->session->get($this->key);

        if ($id === null) {
            return null;
        }

        $this->user = User::find($id);
        return $this->user;
    }

    public function id()
    {
        return $this->user->id ?? null;
    }

    public function check()
    {
        return (bool) $this->user();
    }

    public function attempt(string $email, string $password)
    {
        $user = User::query()->where("email", "=", $email);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->password)) {
            return false;
        }
        
        $this->login($user);

        return true;
    }

    private function login(User $user)
    {
        $this->session->set($this->key, $user->id);
        $this->session->regenerate();
        $this->user = $user;
    }

    public function logout()
    {
        $this->session->remove($this->key);
        $this->session->regenerate();
        $this->user = null;
    }
}