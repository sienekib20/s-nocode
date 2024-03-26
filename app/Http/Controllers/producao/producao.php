<?php

namespace App\Http\Controllers\producao;

use App\Http\Controllers\Controller;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;

class producao extends Controller
{

    public function index(Request $request)
    {
        $data = [];

        $file = storage_path() . 'templates/usuarios/' . $request->dominio . '/index.php';

        $file = rtrim($file, '/');

        if (file_exists($file)) {
            return view($request->dominio . ':site.producao.producao', compact('file'));
        }

        // TODO: coloque o seu código   

        return view($request->dominio . ':site.producao', compact('data'));
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
