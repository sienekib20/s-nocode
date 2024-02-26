<?php

namespace App\Http\Controllers;

use Sienekib\Mehael\Database\Factory\DB;

class browse extends Controller
{
    public function load()
    {
        $templates = DB::raw('select t.template_id, t.uuid, t.titulo, t.referencia, t.status, t.autor, (select tipo_template from tipo_templates where tipo_template_id = t.tipo_template_id) as tipo, (select file from files where file_id = t.file_id) as capa from templates as t');
      

        $tipo = DB::table('tipo_templates')->select('tipo_template, tipo_template_id')->get();
        $categorias = DB::table('categorias')->get();

        return view('Browse:site.browse', compact('templates', 'tipo', 'categorias'));
    }
}