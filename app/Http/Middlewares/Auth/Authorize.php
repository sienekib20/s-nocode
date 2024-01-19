<?php

namespace App\Http\Middlewares\Auth;

use Sienekib\Mehael\Support\Session;

class Authorize
{
    public function handle()
    {
        $restricted = Session::has('user');

        if (!$restricted) {

            return redirect()->route('entrar');
        }
    }
}
