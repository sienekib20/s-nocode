<?php

namespace App\Http\Middlewares\Auth;

use Sienekib\Mehael\Support\Session;

class Authorize
{
    public function handle()
    {
        if (!Session::has('user')) {
            return redirect()->route('entrar');
        }
    }
}
