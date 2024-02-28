<?php

namespace Sienekib\Mehael\Http\Src;

trait Uri
{
    public function uri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';

        // Verifique se existe uma string de consulta (query string)
        $queryString = $_SERVER['QUERY_STRING'] ?? '';

        // Se houver uma query string, concatene-a à URI
        if (!empty($queryString)) {
            $uri .= '?' . $queryString;
        }

        $prefix = $this->prefix();

        $uri = explode($prefix, $uri);

        return end($uri);
    }

    /*public function uri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';

        $prefix = $this->prefix();

        $uri = explode($prefix, $uri);

        return end($uri);
    }*/

    private function prefix()
    {
        return basename(abs_path());
    }

    public function path()
    {
        return $this->uri();
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
