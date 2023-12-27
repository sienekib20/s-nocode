<?php

namespace App\Http\Controllers;

use Sienekib\Mehael\Database\Factory\DB;

class browse extends Controller
{
    public function load()
    {
        $templates = DB::table('templates')->select('*')->get();

        $tipo = DB::table('tipo_templates')->select('tipo_template, tipo_template_id')->get();

        return view('Browse:site.browse', compact('templates', 'tipo'));
    }

}