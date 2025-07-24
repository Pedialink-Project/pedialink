<?php

namespace Library\Framework\Http;

class Response
{
    protected $content;
    protected int $status = 200;
    protected $headers = [];

    public function __construct($content = '', int $status = 200)
    {
        $this->content = $content;
        $this->status  = $status;
    }

    public function header($key, $value)
    {
        $this->headers[$key] = $value;
    }

    public function asJson()
    {
        $this->content = json_encode($this->content);
        $this->header('Content-Type', 'application/json');
        return $this;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public function send()
    {
        http_response_code($this->status);
        foreach ($this->headers as $k => $v) {
            header("$k: $v");
        }
        echo $this->content;
    }

    public static function redirect(string $url, int $status = 302)
    {
        $response = new self('', $status);
        $response->header('Location', $url);
        return $response;
    }
}