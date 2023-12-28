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
		$template = DB::table('templates')->select('referencia')->where('uuid', '=', $request->uuid)->get()[0];


		$file = rtrim(storage_path() . "templates/defaults/" . $template->referencia . "/index.php", '/');

		if (file_exists($file)) {

			return view('web editor:app.gjs-editor', compact('file'));
		}

		return view('Not found:app.errors.not-found');
	}
}
