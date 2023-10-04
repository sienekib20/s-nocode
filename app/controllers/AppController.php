<?php

namespace app\controllers;

use app\models\App;

class AppController
{
    public function index()
    {   
        $data = App::all();

        var_dump($data);exit;

        return view('app.index');
    }

    public function update($request)
    {
    }
}
