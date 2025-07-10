<?php

namespace Library\Framework\Http;

class Request
{
    public string $method;
    public string $uri;
    public array $headers;
    public array $get;
    public array $post;

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

        return $request;
    }

    public function input($key, $default = null)
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }
}