<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;
use Sienekib\Mehael\Support\Auth;

class editor extends Controller
{

    public function web_builder()
    {
        $data = [];

        // TODO: coloque o seu código

        return view('Web Creator:app.webb.index', compact('data'));
    }

    public function open_template_edit(Request $request)
    {
        $referencia = DB::table('templates')
            ->where('uuid', '=', $request->uuid)
            ->select('template_id, referencia')
            ->get()[0];

        $template = $referencia;
        $dominio = $request->dominio;

        $d = DB::table('temp_parceiros')->where('dominio', '=', $request->dominio)->get();

        if (!empty($d)) {
            $indexContent = storage_path() . "templates/usuarios/{$request->dominio}/index.php";
            return view('web editor:site.gjs-edit', compact('indexContent', 'dominio', 'template'));
        }

        return view('Not found:app.errors.not-found');
    }

    public function abrir_editor_template($request)
    {
        $referencia = DB::table('templates')
            ->where('uuid', 'like', $request->uuid . '%')
            ->select('template_id, referencia')
            ->get()[0];

        $template = $referencia;
        $dominio = $request->dominio;

        if ($referencia) {
            $filePath = __template_path("{$referencia->referencia}/index.html");
            $red = "{$referencia->referencia}/index.html";

            if (file_exists($filePath)) {
                $indexContent = file_get_contents($filePath);

                // Caminho base para os recursos
                $resourceBasePath = __template("{$referencia->referencia}/");

                // Processa os caminhos relativos dos recursos
                $indexContent = preg_replace_callback(
                    '/(src|href)\s*=\s*["\']([^"\']+)["\']/i',
                    function ($matches) use ($resourceBasePath) {
                        // Se for um arquivo CSS, adicione um parâmetro de consulta com o timestamp atual
                        if (pathinfo($matches[2], PATHINFO_EXTENSION) === 'css') {
                            return $matches[1] . '="' . $resourceBasePath . $matches[2] . '?v=' . time() . '"';
                        }
                        return $matches[1] . '="' . $resourceBasePath . $matches[2] . '"';
                    },
                    $indexContent
                );
                //dd($indexContent);
                return view('web editor:site.gjs-editor', compact('indexContent', 'template', 'dominio', 'red'));
                // return view('web editor:site.editor', compact('indexContent', 'template', 'dominio'));
            }
        }
        return view('Not found:app.errors.not-found');
    }

    public function open_template(Request $request)
    {

        $check = DB::table('temp_parceiros')
            ->select('count(*) as count')
            ->where('parceiro_id', '=', Auth::user()->id)
            ->get();

        // Lógica PHP para verificar a condição
        if (!empty($check)) {
            if ($check[0]->count == 2) {
                session()->setFlashMessage('limit', 'Já atingiste o limite de adesão de templates');
                // Gere o script JavaScript para redirecionamento de volta
                // Gere o script JavaScript para exibir a mensagem e fechar a janela
                echo '<script>';
                echo 'alert("Já atingiste o limite de adesão de templates. O máximo são 2 templates");'; // Mostra a mensagem
                echo 'window.close();';  // Fecha a janela
                echo '</script>';
                exit(); // Saia do script PHP
            }
            // Se a condição for atendida, continue com a lógica PHP
            return $this->abrir_editor_template($request);
        }
        // Se a condição for atendida, continue com a lógica PHP
        return $this->abrir_editor_template($request);
    }
}
