<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;
//use \ZipArchive;

class templates extends Controller
{

	public function index()
	{
		$templates = DB::raw('select t.template_id, t.uuid, t.titulo, t.referencia, (select tipo_template from tipo_templates where tipo_template_id = t.tipo_template_id) as tipo, (select file from files where file_id = t.file_id) as capa from templates as t');

		//dd(preg_match("/^editor\/([a-zA-Z0-9\-]+)$/", 'editor/e1bb6fb4-0bf4-4a2e-a986-3561121f7aee'));

		// TODO: coloque o seu código

		return view('Templates:app.site.explorar', compact('templates'));
	}

    private function saveImageFile()
    {

    }

	// Cria um registo na DB

	public function store(Request $request)
	{
		/*$build = "<style>{$request->code_css}</style>";
		$build .= $request->code_html;
		$build .= "<script>{$request->code_js}</script>";*/
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
		   $arquivo_zip = $_FILES["zip"]["tmp_name"];
		   $destino = storage_path(); // Substitua pelo caminho real

		   //dd(pathinfo($_FILES["zip"]["name"], PATHINFO_EXTENSION));
		   //dd($_FILES["zip"]["name"]);
		   
		   // Verifica se o arquivo é um arquivo zip
		   if (pathinfo($_FILES["zip"]["name"], PATHINFO_EXTENSION) == 'zip') {
		       // Descompacta o arquivo zip
		       $zip = new \ZipArchive;
		       if ($zip->open($arquivo_zip) === TRUE) {
		           //dd($zip->filename);
		           $zip->extractTo($destino);
		           $zip->close();
		           echo 'Arquivo zip descompactado com sucesso!'; return;
		       } else {
		           echo 'Falha ao descompactar o arquivo zip.'; return;
		       }
		   } else {
		       echo 'Por favor, envie um arquivo zip válido.'; return;
		   }
		    
		}
		return redirect()->route('http://localhost:8001', true);

		return response()->json($request->all());

		$extension = $request->base64FileExtension('cover_name');
		$template_cover = $request->base64File('cover_file');
		$template_name = sha1(date('YmdHi').$request->cover_name).".$extension";
		
		$template_path_default = $this->system->build_path('storage', 'templates.defaults.'.$request->referencia);


	    $arquivo_zip = $request->base64File('zipFile');//;$_FILES["zip"]["tmp_name"];
	    $destino = $template_path_default; //storage_path(); // Substitua pelo caminho real
	    $zip_extension = explode('.', $request->zipName)[1];

	    //dd(pathinfo($_FILES["zip"]["name"], PATHINFO_EXTENSION));
	    //dd($_FILES["zip"]["name"]);
	    /*  REASON
	    	serials:
	    	RSN500-0000-634060-BAT3-PBNS-LV8H
	    	RSN500-0000-694182-6LVR-S87M-UYZ8
	    	RSN500-0000-026143-K73S-7TPT-4CTY
	    	RSN500-0000-218295-AG9B-4HUQ-V5PJ
	    	RSN500-0000-146147-F7C9-72QQ-47XH
	    	RSN500-0000-179743-6JBS-XURQ-CURJ
	    	RSN500-0000-200116-EKXK-J8T8-F5RS
	    	RSN500-0000-325674-2QWL-98CJ-9K4F
	    	RSN500-0000-240303-7P4V-ZNSG-4A4Y
	     */
	    
	    // Verifica se o arquivo é um arquivo zip
	    if ($zip_extension == 'zip') {
	        // Descompacta o arquivo zip
	        $zip = new \ZipArchive();	
	        if (!class_exists('ZipArchive')) {
	            return response()->json('Extensão ZipArchive não está disponível no seu servidor.');
	        }
	        if (!is_readable($arquivo_zip)) {
	            $response = 'Não é possível ler o arquivo ZIP.';
	        	return response()->json('ilegível');	
	        }
	        return response()->json($zip);
	        if ($zip->open($arquivo_zip) === TRUE) {
	            //dd($zip->filename);
	        	$zip->extractTo($destino);
	            $zip->close();
	            $response = 'Arquivo zip descompactado com sucesso!';
	        } else {
	            $response = 'Falha ao descompactar o arquivo zip.';
	        }
	    } else {
	        $response = 'Por favor, envie um arquivo zip válido.';
	    }

	    return response()->json($response);
			
		//return 0;

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
