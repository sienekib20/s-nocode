<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;

class pacotes extends Controller
{

	public function index()
	{
		$data = DB::table('pacotes')->get();
    $enviar = []; $index = 0;
    foreach($data as $datum) {
      $enviar[$index]['pacote'] = $datum->pacote;
      $enviar[$index]['desc'] = explode(';',$datum->descricao);
      $index++;
    }

		// TODO: coloque o seu código

		return view('pacotes:site.pacotes', compact('enviar'));
	}

	// Cria um registo na DB

	public function store(Request $request)
	{
		// TODO: coloqe o seu código

		return redirect()->route('rota.de.redirecionamento');
	}

	// Pega um registo(s) na DB

	public function read(Request $request)
	{
		$data = [];

		// TODO: coloqe o seu código

		return response()->json($data);
	}

	// Atualizações de um ou + registos na DB

	public function update(Request $request)
	{
		// TODO: coloqe o seu código

		return redirect()->backWith('success', 'mensagem de sucesso');
	}

	// Apaga um registo na DB

	public function delete(Request $request)
	{
		DB::table('tabela')->where('id', '=', $request->id)->delete();

		// TODO: coloqe o seu código

		return redirect()->back();
	}
}
