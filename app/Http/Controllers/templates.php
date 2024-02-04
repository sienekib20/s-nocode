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
        if ($request->method() == "POST") {
            $zip_archive = $request->fileTempName('zip');
            $zip_folder_name = $request->fileOriginalName('zip');;
            $zip_destination = $this->system->build_path('storage', 'templates.defaults');

            if ($request->fileExtension('zip') == 'zip') {
                // Descompacta o arquivo zip
                $zip = new \ZipArchive;
                if ($zip->open($zip_archive) === TRUE) {
                    $zip->extractTo($zip_destination);
                    $zip->close();

                    $cover_destination = $this->system->build_path('storage', 'templates.defaults.' . $zip_folder_name . '.cover');
                    $cover_extension = $request->fileExtension('cover');

                    $cover_tmp = $request->fileTempName('cover');
                    $template_name = date('YmdHi') . md5( $request->fileOriginalName('cover'));
                    $template_name .= '.';
                    $template_name .= $cover_extension;

                    $cover_file_id = DB::table('files')->insertId(['file' => $template_name]);

                    if (move_uploaded_file($cover_tmp, $cover_destination . $template_name )) {
                        $result = DB::table('templates')->insert(['uuid' => Uuid::uuid4()->toString(), 'titulo' => $request->titulo, 'autor' => $request->autor, 'referencia' => $zip_folder_name, 'editar' => $request->editar, 'status' => $request->status, 'preco' => $request->preco ?? '0.00', 'descricao' => $request->descricao, 'template' => '', 'tipo_template_id' => $request->tipo_template, 'file_id' => $cover_file_id]);
                        $response = $result ? 'Salvo com sucesso' : 'Erro ao salvar';

                        return response()->json($response);
                    } else {
                        return response()->json('Erro ao salvar o arquivo: cover');
                    }
                } else {
                    return response()->json('Erro ao descompactar');
                }
            } else {
                return response()->json('O arquivo zip que inseriste está errado, verifica a extensão .zip');
            }
        }

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
        $referencia = DB::table('templates')->where('uuid', '=', $request->template)->select('referencia')->get();

        if (!empty($referencia)) {
            $file = storage_path() . "templates/defaults/" . $referencia[0]->referencia . "/index.html";
            $file = rtrim($file, '/');

            if (file_exists($file)) {
                return view('Preview:app.site.preview', compact('file'));
                //return view('web editor:app.gjs-editor', compact('file'));
            }
        }

        return view('404:app.errors.not-found');
    }
}
