<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;

class templates extends Controller
{

	public function index()
	{
		$templates = DB::raw('select t.template_id, t.uuid, t.titulo, t.referencia, (select tipo_template from tipo_templates where tipo_template_id = t.tipo_template_id) as tipo, (select file from files where file_id = t.file_id) as capa from templates as t');

		//dd(preg_match("/^editor\/([a-zA-Z0-9\-]+)$/", 'editor/e1bb6fb4-0bf4-4a2e-a986-3561121f7aee'));

		// TODO: coloque o seu código

		return view('Templates:app.site.explorar', compact('templates'));
	}

	// Cria um registo na DB

	public function store(Request $request)
	{
		$build = "<style>{$request->code_css}</style>";
		$build .= $request->code_html;
		$build .= "<script>{$request->code_js}</script>";

		$extension = $request->base64FileExtension('cover_name');
		$template_cover = $request->base64File('cover_file');
		$template_name = sha1(date('YmdHi').$request->cover_name).".$extension";
		
		$template_path_default = $this->system->build_path('storage', 'templates.defaults.'.$request->referencia);

		$template_path_cover = $this->system->build_path('storage', 'templates.defaults.'.$request->referencia.'.cover');

		if (file_put_contents($template_path_cover.$template_name, $template_cover)) {
			
			$fileId = DB::table('files')->insertId(['file' => $template_name]);

			$result = DB::table('templates')->insert([
			    'uuid' => Uuid::uuid4()->toString(),
			    'titulo' => $request->titulo,
			    'autor' => 'Sílica',
			    'referencia' => $request->referencia,
			    'editar' => $request->editar,
			    'status' => $request->status,
			    'preco' => $request->preco,
			    'descricao' => $request->descricao,
			    'template' => $build,
			    'tipo_template_id' => $request->tipo,
			    'file_id' => $fileId
			]);

			if ($result == true) {
				if (file_put_contents($template_path_default.'index.php', $build)) {
					$response = 'Salvo com sucesso';
				} else {
					$response = 'Erro no upload de template';
				}
			} else {
				$response = 'Erro ao salvar';
			}

		} else {
			$response = 'Algo deu errado';
		}
		return response()->json($response);
	}

	// Pega um registo(s) na DB

	public function read(Request $request)
	{
		$data = [];

		// TODO: coloqe o seu código

		return response()->json($data);
	}

	public function temp_usuario(Request $request)
	{
		$data = [];
		$tipo_templates = $this->getTipoTemplate();
		$templates = DB::raw('select t.template_id, t.uuid, t.titulo, t.referencia, (select tipo_template from tipo_templates where tipo_template_id = t.tipo_template_id) as tipo, (select file from files where file_id = t.file_id) as capa from templates as t');

		return view('Meus templates:app.site.meus-templates', compact('data', 'tipo_templates', 'templates'));
	}

	public function getTipoTemplate()
	{
		return DB::table('tipo_templates')->select('tipo_template_id, tipo_template')->get();
	}

	public function preview(Request $request)
	{
		$file = rtrim(storage_path() . "templates/defaults/".$request->template."/index.php", '/');
		
		if (file_exists($file)) {
			return view('Prever template:app.site.preview', compact('file'));
			//return view('web editor:app.gjs-editor', compact('file'));
		}

		return view('Not found:app.errors.not-found');

	}
}
