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

        // TODO: coloque o seu código

        return view('pacotes:site.pacotes', compact('enviar'));
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

        //dd($enviar);

        return view('aderir pacote:site.aderir-pacote', compact('package', 'descricao', 'text', 'enviar'));
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
