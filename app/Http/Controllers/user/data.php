<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;
use Sienekib\Mehael\Support\Auth;

class data extends Controller
{

    public function carregar(Request $request)
    {
        /*$data = DB::raw('select tp.dominio, tp.created_at, (select titulo from templates where template_id = tp.template_id) as titulo, (select uuid from templates where template_id = tp.template_id) as template_uuid, (select status from templates where template_id = tp.template_id) as status, (select preco from templates where template_id = tp.template_id) as preco, (select if(status="Grátis","30 dias", "90 dias") from templates where template_id = tp.template_id) as prazo, (select tipo_template from tipo_templates where tipo_template_id = (select tipo_template_id from templates where template_id = tp.template_id)) as categoria, (select conta_id from contas where conta_id = ?) from temp_parceiros as tp where parceiro_id = ?', [$request->id, $request->id]);*/
        $data = DB::raw('select tp.dominio, tp.created_at, (select titulo from templates where template_id = tp.template_id) as titulo, (select uuid from templates where template_id = tp.template_id) as template_uuid, (select status from templates where template_id = tp.template_id) as status, (select preco from templates where template_id = tp.template_id) as preco, (select if(status="Grátis","30 dias", "90 dias") from templates where template_id = tp.template_id) as prazo, (select conta_id from contas where conta_id = ?) from temp_parceiros as tp where parceiro_id = ?', [$request->id, $request->id]);

        $templateUsuario = DB::table('temp_parceiros')->select('count(template_id) as total')->where('parceiro_id', '=', $request->id)->get()[0];

        $subscricoes = 0;
        $encomendas = 0;

        //return view('Home:app.site.index', compact('data'));
        return view('Meus dados:site.user-data', compact('data', 'templateUsuario', 'subscricoes', 'encomendas'));
    }


    public function choose(Request $request)
    {
        if ($request->uuid == 'default') {
            $template = [];
            return view('Escolha:site.choose', compact('template'));
        }
        $template = DB::raw('select t.referencia, (select tipo_template from tipo_templates where tipo_template_id = t.tipo_template_id) as categoria, (select categoria from categorias where categoria_id = t.categoria_id) as branch, t.uuid, t.template_id, t.autor, t.titulo, t.descricao, t.template, t.status, t.preco,(select file from files where file_id = t.file_id) as capa, (select count(temp_parceiro_id) from temp_parceiros where template_id = t.template_id) as quantidade from templates as t where uuid like ?', [$request->uuid.'-%'])[0];
        // -> será adicionado no proximo migrate fresh seed :
        // (select classificacao from classificacaos where template_id = t.template_id) as classificacao

        $referencia = DB::table('templates')
            ->where('uuid', 'like', $request->uuid.'-%')
            ->select('template_id, referencia')
            ->get()[0];

        $dominio = $request->dominio;

        if ($referencia) {
            $filePath = __template_path("defaults/{$referencia->referencia}/index.html");

            if (file_exists($filePath)) {
                $indexContent = file_get_contents($filePath);

                // Caminho base para os recursos
                $resourceBasePath = "/storage/templates/defaults/{$referencia->referencia}/";

                // Processa os caminhos relativos dos recursos
                $indexContent = preg_replace_callback(
                    '/(src|href)\s*=\s*["\']([^"\']+)["\']/i',
                    function ($matches) use ($resourceBasePath) {
                        // Se for um arquivo CSS, adicione um parâmetro de consulta com o timestamp atual
                        if (pathinfo($matches[2], PATHINFO_EXTENSION) === 'css') {
                            return $matches[1] . '="' . $matches[2] . '?v=' . time() . '"';
                        }
                        return $matches[1] . '="' . $resourceBasePath . $matches[2] . '"';
                    },
                    $indexContent
                );
                //dd($indexContent);
                // return view('web editor:site.editor', compact('indexContent', 'template', 'dominio'));
            }
        }
        
        return view('Escolha:site.choose', compact('template', 'indexContent'));
    }

    private function save_in_database($dominio, $template, $template_id)
    {
        if ($this->salvar_no_storage($dominio, $template)) {
            if (Auth::check()) {
                return DB::table('temp_parceiros')->insert(['parceiro_id' => Auth::user()->id, 'template_id' => $template_id, 'dominio' => $dominio]);
            }
            return 'Usuário não autenticado';
        }
        return false;
    }

    public function save_template_edit(Request $request)
    {
        // caminho pra registar novo template: storage/templates/usuarios/NOME_DO_USUARIO/ARQUIVO_UNICO_AQUI.html
        if (is_null($request->template)) {
            return response()->json('Dados inválidos');
        }

        // Verifica se o usuario tem template do mesmo nome em uso
        $count = DB::table('temp_parceiros')->select('count(temp_parceiro_id) as total')->where('template_id', '=', $request->id)->get()[0];

        if ($count->total > 0) {
            $dominio = DB::table('temp_parceiros')->select('dominio')->where('template_id', '=', $request->id)->get();
            foreach ($dominio as $d) {
                if ($d->dominio == $request->dominio) {
                    $response = ($this->salvar_no_storage($request->dominio, $request->template)) ?
                        'Salvo com sucesso' : 'Erro ao criar o registo';
                    return response()->json($response);
                }
            }
        }

        return response()->json('Algo deu errado');
    }

    public function save_template(Request $request)
    {
        // caminho pra registar novo template: storage/templates/usuarios/NOME_DO_USUARIO/ARQUIVO_UNICO_AQUI.html
        if (is_null($request->template)) {
            return response()->json('Dados inválidos');
        }

        // Verifica se o usuario tem template do mesmo nome em uso
        $count = DB::table('temp_parceiros')->select('count(temp_parceiro_id) as total')->where('template_id', '=', $request->id)->get()[0];

        if ($count->total == 0) {

            $response = ($this->save_in_database(
                $request->dominio,
                $request->template,
                $request->id
            )) ? 'Salvo com sucesso' : 'Erro ao criar o registo';

            return response()->json($response);
        } else if ($count->total > 0) {
            $dominio = DB::table('temp_parceiros')->select('dominio')->where('template_id', '=', $request->id)->get();
            foreach ($dominio as $d) {
                if ($d->dominio == $request->dominio) {
                    return response()->json('Este dominio: `' . $request->dominio . '` já foi usado. Tente outro');
                }
            }

            $response = ($this->save_in_database(
                $request->dominio,
                $request->template,
                $request->id
            )) ? 'Salvo com sucesso' : 'Erro ao criar o registo';

            return response()->json($response);
        }

        return response()->json('Algo deu errado');
    }

    public function validar_uso(Request $request)
    {
        if (is_null($request->dominio)) {
            return response()->json('Dominio inválido');
        }

        if ($this->usario_já_usa_este_template($request->usuario, $request->id)) {
            $templateName = DB::table('templates')->select('titulo')->where('template_id', '=', $request->id)->get()[0];
            return response()->json('Já publicaste esse template "' . $templateName->titulo . '", dirija-te no menu: meus dados');
        }

        if ($this->salvar_no_storage($request->dominio, $request->template)) {

            $result = DB::table('temp_parceiros')->insert(['template_id' => $request->id, 'parceiro_id' => $request->usuario, 'dominio' => $request->dominio]);
            $response = ($result) ? 'Salvo com sucesso' : 'Erro ao salvar';
        } else {
            $response = 'Algo deu errado';
        }

        return response()->json($response);
    }

    private function salvar_no_storage($dominio, $template)
    {

        
        $template_path_default = $this->system->build_path('storage', 'templates.usuarios.' . $dominio);

        return file_put_contents($template_path_default . 'index.php', $template);
    }

    private function usario_já_usa_este_template($usuario_id, $template_id)
    {
        $resultado = DB::table('temp_parceiros')->select('count(temp_parceiro_id)')->where('parceiro_id', '=', $usuario_id)->where('template_id', '=', $template_id)->get();
        return ($resultado == 0) ? false : true;
    }
}
