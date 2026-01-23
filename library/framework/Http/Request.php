<?php

namespace Library\Framework\Http;

class Request
{
    public string $method;
    public string $uri;
    public array $headers;
    public array $get;
    public array $post;
    public array $files;

    /**
     * Capture incoming requests
     * @return Request Returns a request object
     */
    public static function capture(): Request
    {
        $request = new static;

        $request->method = $_SERVER["REQUEST_METHOD"];
        $request->uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $request->headers = getallheaders();
        $request->get = $_GET;
        $request->post = $_POST;
        $request->files = $_FILES;

        return $request;
    }

    public function input($key, $default = null)
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function query($key, $default = null)
    {
        return $this->get[$key] ?? $default;
    }

    public function file($key)
    {
        return $this->files[$key] ?? null;
    }
}