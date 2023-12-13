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

	public function open_template($template)
    {
        $reference = DB::select('select referencia from templates where template_id = ?', [$template->id])[0];
        $to = [
            //'url' => url('localhost', 7000) . 'nocode/' . $reference->referencia
        ];
        //var_dump($reference->referencia);exit;
        //header('Location: /nocode/' . $reference->referencia);
        return view('app.editor', compact('to'));
    }

	
}
