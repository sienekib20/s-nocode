<?php

namespace Mehael\Http;

class Request
{
    // Armazena todos os dados da requisição

    private array $body = [];

    // Método constructor

    public function __construct()
    {
        $this->body = $this->populate();
    }

    // Adiciona um paramétro passado na requisição

    public function bind(array $params)
    {
        foreach ($params as $key => $value) {
            $_REQUEST[$key] = $value;
        }
        $this->body = $this->populate();
    }

    // Carrega todos os dados da requisição

    private function populate()
    {
        $data = [];

        foreach ($_REQUEST as $key => $value) {
            $data[$key] = strip_tags($value);
        }

        return $data;
    } 

    // Retorna o método da requisição
    /* @return string */

    public function method() : string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    // Retorna a uri da requisição
    /* @return string */

    public function uri() : string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';

        // Posição do ? na uri
        $position = strpos($path, '?');

        if ($position !== false) {
            $path = explode('?', $path)[0];
        }

        // Fatia a uri com o dado prefixo
        $path = explode($this->prefix(), $path);

        return end($path);
    }

    // Pega o prefixo actual
    /* @return string */

    public function prefix() : string
    {

        if (env('APP_NAME') !== app()->name) {
            throw new \Exception("Nome do aplicativo não correspondente");
        }
        return basename(abs_path());
    }


    public function __get($key)
    {
        return $this->body[$key] ?? null;
    }
}
