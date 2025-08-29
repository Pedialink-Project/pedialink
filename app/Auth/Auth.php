<?php

namespace App\Auth;

use App\Models\User;
use Library\Framework\Session\SessionManager;

/**
 * Auth is a special class that exposes common
 * methods for handling user session.
 * 
 * Auth is also a singleton so the current instance 
 * can be directly accessed from Application instance.
 * 
 * NOTE for cs 28 members: Use the auth() helper method 
 * whenever you need to use Auth::class methods!
 * 
 * NOTE: This is not inside library/framework because
 * it depends on the existence of User model. Due to this
 * Auth class is not inside the framework package. This is also
 * not inside Services because Auth class is not a extraction
 * of huge controller logic. Auth class can be used
 * anywhere in the codebase (except core folders) to get
 * the current user session!
 * 
 */
class Auth
{
    private SessionManager $session;
    private ?User $user = null;
    private string $key = "auth_user_id";

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Get the current user. Returns null
     * if no user is authenticated
     * 
     * @return User|null
     */
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

    /**
     * Get the current id of the user.
     * Returns null if no user is
     * authenticated
     * 
     * @return int|mixed|null
     */
    public function id()
    {
        return $this->user->id ?? null;
    }

    /**
     * Check if an authenticated user exists
     * in current session.
     * 
     * @return bool
     */
    public function check()
    {
        return (bool) $this->user();
    }

    /**
     * Attempts to login the given set of credentials
     * 
     * @param string $email Email of the user
     * @param string $password Password of the user
     * @param mixed $role Optional role authenticated user based on roles
     * @param mixed $operator Optional operator to compare the role parameter
     * @return bool Returns true if successfully authenticated or false.
     */
    public function attempt(string $email, string $password, ?string $role = null, $operator = "=")
    {
        $user = null;

        // Optionally added a role based auth attempt if role is provided
        // NOTE: this is because we have separate forms for parent and staff
        if ($role) {
            $user = User::query()
                ->where("email", "=", $email)
                ->where("role", $operator, $role)->first();
        } else {
            $user = User::query()->where("email", "=", $email)->first();
        }

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->password_hash)) {
            return false;
        }
        
        $this->login($user);

        return true;
    }

    /**
     * Directly make the given user the current authenticated
     * user session!
     * 
     * Note: This does not validate details! Use this only if you
     * are performing some other custom verification before calling
     * this method!
     * 
     * Ex: This method is called after successfully registering
     * a user!
     * 
     * @param \App\Models\User $user
     * @return void
     */
    public function login(User $user)
    {
        $this->session->set($this->key, $user->id);
        $this->session->regenerate();
        $this->user = $user;
    }

    /**
     * Clear the current user session and logout.
     * 
     * @return void
     */
    public function logout()
    {
        $this->session->remove($this->key);
        $this->session->regenerate();
        $this->user = null;
    }
}