<?php

namespace Sienekib\Mehael\Http;

class Response
{
    private array $headers = [];
    private int $statusCode = 200;

    public function __construct()
    {
        //$this->setHeader('Access-Control-Allow-Origin', '*');
        $this->setHeader('Access-Control-Allow-Headers', 'Content-Type');
        $this->setHeader('Access-Control-Allow-Credentials', 'true');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $this->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $this->send();
        }
    }

    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function json(mixed $data, $status = 200)
    {
        http_response_code($status);
        $this->setHeader('Content-Type', 'application/json');
        $this->send(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    public function setHeader(string $header, string $value)
    {
        $this->headers[$header] = $value;
    }

    public function send(string $content = '')
    {
        $this->sendHeaders();
        echo $content;
        exit;
    }

    public function sendHeaders()
    {
        if (!headers_sent()) {
            foreach ($this->headers as $header => $value) {
                header("$header: $value");
            }
            http_response_code($this->statusCode);
        }
    }
}
