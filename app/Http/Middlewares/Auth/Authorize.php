<?php

namespace App\Http\Middlewares\Auth;

use Sienekib\Mehael\Support\Session;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Http\Middlewares\Middleware as BaseMiddleware;

class Authorize extends BaseMiddleware
{
    public function handle()
    {
        $request = new Request;
        
        if (!Session::has('user')) {
            
            Session::set('__redirect_url', $request->uri());

            return redirect()->route('entrar');
        }
    }
}