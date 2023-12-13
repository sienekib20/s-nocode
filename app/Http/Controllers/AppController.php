<?php

namespace App\Http\Controllers;

class AppController extends Controller
{
    public function index()
    {
        var_dump('aqui');exit;

        return view('app.site.index');
    }

    public function update($request)
    {
    }
}
