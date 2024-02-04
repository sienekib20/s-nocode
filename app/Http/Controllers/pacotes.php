<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;

class pacotes extends Controller
{

    public function index()
    {
        $enviar = $this->get_planos();

        $tipo = DB::table('tipo_templates')->get();

        // TODO: coloque o seu código

        return view('pacotes:site.pacotes', compact('enviar', 'tipo'));
    }

    private function get_planos(...$condition)
    {
        $data = (!empty($condition)) ? DB::table('pacotes')->where($condition[0], $condition[1], $condition[2])->get() :
            DB::table('pacotes')->get();
        $enviar = [];
        $index = 0;

        foreach ($data as $datum) {
            $enviar[$index]['id'] = $datum->pacote_id;
            $enviar[$index]['pacote'] = $datum->pacote;
            $enviar[$index]['desc'] = explode(';', $datum->descricao);
            $index++;
        }
        return $enviar;
    }

    public function aderir(Request $request)
    {
        $package = $descricao = [];
        $text = '';

        if (!empty($data = DB::table('pacotes')->where('pacote_id', '=', $request->id)->get())) {
            $package = $data[0];
            $descricao = explode(';', $package->descricao);
            $text = ($package->pacote_id == 1) ? 'Este é um plano inicial, poupe o teu esforço' : '';
        }
        
        $enviar = $this->get_planos('pacote_id', '<>', $request->id);
        $current_plane_id = $this->get_planos('pacote_id', '=', $request->id);

        //dd($enviar);

        return view('aderir pacote:site.aderir-pacote', compact('package', 'descricao', 'text', 'enviar', 'current_plane_id'));
    }

    public function adesao_planos(Request $request)
    {
        // → Se o usuario já aderiu a esse plano e ainda n exiprou
        // ele nao pode aderir outra vez, a menos que expire

        // → Se já aderiu e está em análise n pode tambem aderir outra vez 
        // a menos que tenha sido rejeitado
        $data = DB::table('adesao_pacotes')->where('cliente_id', '=', $request->current_user_id)->where('cliente_id', '=', $request->plano_id)->get();

        if (!empty($data)) {

            if ($data[0]->estado_pacote == 1) {
                session()->setFlashMessage('pacote', 'o teu pedido está em análise, não pode aderir até receber uma resposta');
                return redirect()->route('/aderir/'. $request->plano_id);
            }

            if ($data[0]->estado_pacote == 2) {
                // Calcula o prazo de uso, se n expirou ↓ 
                session()->setFlashMessage('pacote', 'já aderiste este pacote, espera até expirar pra renovar');
                return redirect()->route('/aderir', $request->plano_id);
            }

            $this->save_adesao_pacotes($request->current_user_id, $request->plano_id);
        }
        
        $this->save_adesao_pacotes($request->current_user_id, $request->plano_id);

        return redirect()->route('/aderir', $request->plano_id);
    }

    private function save_adesao_pacotes($id_cliente, $id)
    {
        return DB::table('adesao_pacotes')->insert([
            'cliente_id' => (int) $id_cliente,
            'pacote_id' => (int) $id,
            'estado_pacote' => 1
        ]);
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
