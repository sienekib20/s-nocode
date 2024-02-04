<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;

class editor extends Controller
{

    public function web_builder()
    {
        $data = [];

        // TODO: coloque o seu código

        return view('Web Creator:app.webb.index', compact('data'));
    }

    public function open_template(Request $request)
    {
        $template = DB::table('templates')->select('template_id, referencia')->where('uuid', '=', $request->uuid)->get()[0];

        $dominio = $request->dominio;

        $file = storage_path() . "templates/defaults/" . $template->referencia . "/index.html";
        $file = rtrim($file, '/');

        if (file_exists($file)) {

            return view('web editor:site.gjs-editor', compact('file', 'template', 'dominio'));
        }

        return view('Not found:app.errors.not-found');
    }
}
