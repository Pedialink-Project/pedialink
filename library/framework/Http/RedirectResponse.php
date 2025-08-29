<?php
// library/framework/Http/RedirectResponse.php

namespace Library\Framework\Http;

use Library\Framework\Session\SessionManager;

/**
 * RedirectResponse class extends from base Response class
 * to facilitate redirecting capabilities in this MVC app.
 * 
 * The following features are available:
 * 
 *  - Flash variables
 *  - Input data
 *  - Error data
 */
class RedirectResponse extends Response
{
    protected SessionManager $session;

    public function __construct(string $url, int $status = 302)
    {
        parent::__construct('', $status);
        $this->header('Location', $url);

        // resolve session from container
        $this->session = app()->make(SessionManager::class);
    }

    /**
     * Attaches arbitrary flash data to the next request
     * 
     * @param string $key
     * @param mixed $value
     * @return RedirectResponse
     */
    public function with(string $key, $value): self
    {
        $this->session->flash($key, $value);
        return $this;
    }

    /**
     * Attach old input data in form of array for the next request (to flash)
     * @param array $input
     * @return RedirectResponse
     */
    public function withInput(array $input): self
    {
        // standard key name _old_input
        $this->session->flash('_old_input', $input);
        return $this;
    }

    /**
     * Attach validation errors in the form of array (field => messages)
     * to flash for the next request
     * 
     * @param array $errors
     * @return RedirectResponse
     */
    public function withErrors(array $errors): self
    {
        $this->session->flash('_errors', $errors);
        return $this;
    }

    /**
     * Short-hand flash message
     */
    /**
     * Short hand flash messages to be attached to the next request.
     * 
     * NOTE: This is just a base idea and may be useful for toasts in the
     * future. (Discuss in our whatsapp group if you have any ideas)
     * 
     * @param string $message
     * @param string $type
     * @return RedirectResponse
     */
    public function withMessage(string $message, string $type = 'info'): self
    {
        $this->session->flash('_message', ['type' => $type, 'text' => $message]);
        return $this;
    }
}
