<?php

namespace core\classes;

class Response
{
    private $code;

    public function setHttpResponseCode($code = 200)
    {
        $this->code = $code;

        http_response_code($code);
    }

    public function getHttpResponseCode()
    {
        return $this->code;
    }
}
