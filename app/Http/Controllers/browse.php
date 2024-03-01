<?php

namespace App\Http\Controllers;

use Sienekib\Mehael\Database\Factory\DB;
use Sienekib\Mehael\Http\Request;

class browse extends Controller
{
    public function load()
    {
        $templates = DB::raw('select t.template_id, t.uuid, t.titulo, t.referencia, t.status, t.autor, (select tipo_template from tipo_templates where tipo_template_id = t.tipo_template_id) as tipo, (select categoria from categorias where categoria_id = t.categoria_id) as categoria, (select file from files where file_id = t.file_id) as capa from templates as t');


        $tipo = DB::table('tipo_templates')->select('tipo_template, tipo_template_id')->get();
        $categorias = DB::table('categorias')->get();

        return view('Browse:site.browse', compact('templates', 'tipo', 'categorias'));
    }

    public function get_browse(Request $request)
    {
        /*return response()->json($request->all());
        $requestSize = count($_REQUEST);
        if ($requestSize > 1 && $requestSize < 3) {
            if (!is_null($request->categoria)) {
                $templates = DB::raw('SELECT 
                t.template_id, 
                t.uuid, 
                t.titulo, 
                t.referencia, 
                t.status, 
                t.autor, 
                (SELECT tipo_template FROM tipo_templates WHERE tipo_template_id = t.tipo_template_id) AS tipo, 
                (SELECT categoria FROM categorias WHERE categoria_id = t.categoria_id) AS categoria, 
                (SELECT file FROM files WHERE file_id = t.file_id) AS capa 
            FROM 
                templates AS t 
            WHERE 
                (t.titulo LIKE ? OR t.autor LIKE ?) 
                AND EXISTS (SELECT 1 FROM tipo_templates WHERE tipo_template_id = t.tipo_template_id AND tipo_template = ?)
            ', ["%$request->input%", "%$request->input%"]);
            } else if (!is_null($request->tipo)) {
            }
        }*/

        $templates = DB::raw('select t.template_id, t.uuid, t.titulo, t.referencia, t.status, t.autor, (select tipo_template from tipo_templates where tipo_template_id = t.tipo_template_id) as tipo, (select categoria from categorias where categoria_id = t.categoria_id) as categoria, (select file from files where file_id = t.file_id) as capa from templates as t where titulo like ? or autor like ?', ["%$request->input%", "%$request->input%"]);

        return response()->json($templates);
    }
}
