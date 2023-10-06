<?php

namespace app\controllers;

use app\models\App;

class AppController
{
    public function index()
    {  
        $data = ['amor'];
        
        return view('app.index', compact('data'));
    }

    public function update($request)
    {
    }
}
