<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;

class data extends Controller
{

	public function carregar(Request $request)
	{
		$data = DB::raw('select distinct tp.temp_parceiro_id, (select titulo from templates where template_id = tp.template_id) as titulo, (select status from templates where template_id = tp.template_id) as status, (select preco from templates where template_id = tp.template_id) as preco, (select if(status="Grátis","30 dias", "90 dias") as prazo from templates where template_id = tp.template_id) as prazo, tp.created_at from temp_parceiros as tp where parceiro_id = (select conta_id from contas where nome = ?)', [$request->user]);

		//return view('Home:app.site.index', compact('data'));
		return view('Meus dados:site.user-data', compact('data'));
	}


	public function choose(Request $request)
	{

		$template = DB::raw('select t.template_id, t.autor, t.titulo, t.descricao, t.template, t.status, t.preco,(select file from files where file_id = t.file_id) as capa, (select count(temp_parceiro_id) from temp_parceiros where template_id = t.template_id) as quantidade from templates as t where uuid = ?', [$request->uuid])[0];
		// -> será adicionado no proximo migrate fresh seed : 
		// (select classificacao from classificacaos where template_id = t.template_id) as classificacao

		return view('Escolha:site.choose', compact('template'));
	}

	public function save_template(Request $request)
	{
		$data = $request->html;

		// verificar se template já existe

		return response()->json($data);

	}

	public function validar_uso(Request $request)
	{
		if (is_null($request->dominio)) {
			return response()->json('Dominio inválido');
		}

		if ($this->usario_já_usa_este_template($request->usuario, $request->id)) {
			return response()->json('Já tens esse template em sua posse');
		}

		$template_path_default = $this->system->build_path('storage', 'templates.usuarios.' . $request->dominio);


		if (file_put_contents($template_path_default . 'index.php', $request->template)) {


			$result = DB::table('temp_parceiros')->insert(['template_id' => $request->id, 'parceiro_id' => $request->usuario, /*'dominio' => $request->dominio*/]);

			$response = ($result) ? 'Salvo com sucesso' : 'Erro ao salvar';
		} else {
			$response = 'Algo deu errado';
		}

		return response()->json($response);
	}

	private function usario_já_usa_este_template($usuario_id, $template_id)
	{
		$resultado = DB::table('temp_parceiros')->select('count(temp_parceiro_id)')->where('parceiro_id', '=', $usuario_id)->where('template_id', '=', $template_id)->get();
		return ($resultado == 0) ? false : true;
	}
}
