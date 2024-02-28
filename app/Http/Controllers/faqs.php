<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;

class faqs extends Controller
{

    public function index()
    {
        $asked = DB::table('faqs')->where('acesso', '=', 'liberado')->get();

        // TODO: coloque o seu código

        return view('Perguntas frequentes:site.intro.faqs', compact('asked'));
    }

    public function duvida(Request $request)
    {
        $last = DB::table('faqs')->insertId([
            'uuid' => Uuid::uuid4()->toString(),
            'pergunta' => $request->pergunta,
            'descricao' => $request->descricao,
            'acesso' => 'pedente'
        ]);

        if ($last) {
            $message = 'Enviado com sucesso';
        } else {
            $message = 'Erro ao enviar, tente mais tarde';
        }
        session()->setFlashMessage('purpose', $message);
        return redirect()->route('faqs');
    }

    public function auto_fill(Request $request)
    {

        return response()->json($_SERVER);
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
