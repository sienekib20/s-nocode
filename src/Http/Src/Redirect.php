<?php

namespace Sienekib\Mehael\Http\Src;

class Redirect
{
    public function route(string $uri, $trim_left = false) 
    {
        $uri = '/' . str_replace('.', '/', ltrim($uri, '/'));

        $uri = ($trim_left) ? ltrim($uri, '/') : $uri;
        //$this->setStatusCode(304);

        header('Location: ' . $uri);

        exit;
    }

    public function back()
    {
        dd($_SERVER['HTTP_REFERER']);        
    }

    public function backWith(string $type, string $content)
    {
        
    }
}