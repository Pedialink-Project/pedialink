<?php

namespace Library\Framework\Session;

/**
 * Session manager Handler
 * 
 * Handles server side sessions of the web application.
 */
class SessionManager
{
    protected array $flashed = [];

    /**
     * @var bool
     */
    protected bool $started = false;

    /**
     * Start a server side session
     * @return void
     */
    public function start()
    {
        if ($this->started) {
            return;
        }

        session_start([
            'cookie_lifetime' => 86400,
            'cookie_httponly' => true,
            'use_strict_mode' => true,
        ]);

        $this->started = true;
    }

    /**
     * Retrieve session value
     * @param string $key Session key
     * @param mixed $default Default value to return if session key is not found
     * @return mixed Returns session value
     */
    public function get(string $key, $default = null)
    {
        $this->start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set session value for a particular key
     * @param string $key Session key
     * @param mixed $value Session value
     * @return void
     */
    public function set(string $key, $value)
    {
        $this->start();
        $_SESSION[$key] = $value;
    }

    /**
     * Remove session entry
     * @param string $key Session key
     * @return void
     */
    public function remove(string $key)
    {
        $this->start();
        unset($_SESSION[$key]);
    }

    /**
     * Regenerate the session id while retaining session information
     * @param bool $status Bool value to regenerate or not regenerate session. Default is true
     * @return void
     */
    public function regenerate(bool $status = true)
    {
        $this->start();
        session_regenerate_id($status);
    }

    /**
     * Destroy current server side session
     * @return void
     */
    public function destroy()
    {
        if (!$this->started) {
            return;
        }

        $_SESSION = [];
        session_unset();
        session_destroy();
        $this->started = false;
    }

    /**
     * Create a flash session with key, value pairs to be available
     * on next request. This can be used as a temporary session that
     * exists only for a particular request.
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function flash(string $key, $value): void
    {
        $this->start();
        if (!isset($_SESSION['_flash']) || !is_array($_SESSION['_flash'])) {
            $_SESSION['_flash'] = [];
        }
        $_SESSION['_flash'][$key] = $value;
    }

    /**
     * Get the flash value
     * 
     * NOTE: First calls clears it from session and subsequent calls
     * are read from this->flashed property.
     * 
     * @param string $key
     * @param mixed $default
     */
    public function getFlash(string $key, $default = null)
    {
        $this->start();
        if (array_key_exists($key, $this->flashed)) {
            return $this->flashed[$key];
        }

        if (isset($_SESSION['_flash']) && array_key_exists($key, $_SESSION['_flash'])) {
            $val = $_SESSION['_flash'][$key];
            
            unset($_SESSION['_flash'][$key]); // remove from session (flash is single-use)
            
            $this->flashed[$key] = $val; // store locally for repeated access
            return $val ?? null;
        }

        return $default;
    }

    /**
     * Retrieve and delete arbitrary session key
     * 
     * NOTE: this does not retrieve from the flash property
     * 
     * @param string $key
     * @param mixed $default
     */
    public function pull(string $key, $default = null)
    {
        $this->start();
        if (array_key_exists($key, $_SESSION)) {
            $val = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $val;
        }
        return $default;
    }
}