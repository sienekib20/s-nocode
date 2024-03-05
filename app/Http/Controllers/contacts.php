<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Sienekib\Mehael\Http\Request;

use Sienekib\Mehael\Database\Factory\DB;
use Sienekib\Mehael\Support\Auth;

class contacts extends Controller
{

    public function index()
    {
        if (Auth::check()) {
            $data = Auth::user();
            return view('contactos:site.contacts', compact('data'));
        }

        return view('contactos:site.contacts');
    }

    // Cria um registo na DB
    public function intro_mail(Request $request)
    {
        if (!filter_var($request->email, FILTER_SANITIZE_EMAIL)) {
            return response()->json('email inválido');
        }
        $result = DB::table('mensagens')->insert(['conta_id' => $request->account, 'expediente' => $request->username, 'mail' => $request->email, 'telefone' => '', 'mensagem' => $request->mensagem, 'causas' => 'sem causas']);

        if ($result) {
            return response()->json('enviado com sucesso');
        }

        return response()->json('algo deu errado, tente mais tarde');
    }

    public function own_mail(Request $request)
    {
        $causas = '';
        if (!is_null($request->c_create)) $causas .= $request->c_create.';';
        if (!is_null($request->c_features)) $causas .= $request->c_features.';';
        if (!is_null($request->c_adesao)) $causas .= $request->c_adesao.';';
        if (!is_null($request->c_createc)) $causas .= $request->c_createc.';';
        if (!is_null($request->c_feed)) $causas .= $request->c_feed.';';
        if (!is_null($request->c_outro)) $causas .= $request->c_outro;

        if (!filter_var($request->email, FILTER_SANITIZE_EMAIL)) {
            return response()->json('email inválido');
        }
        $result = DB::table('mensagens')->insert(['conta_id' => $request->account, 'expediente' => $request->username, 'mail' => $request->email, 'telefone' => $request->telefone, 'mensagem' => $request->mensagem, 'causas' => $causas]);

        if ($result) {
            return response()->json('enviado com sucesso');
        }

        return response()->json('algo deu errado, tente mais tarde');
    }

    public function store(Request $request)
    {
        if (!filter_var($request->email, FILTER_SANITIZE_EMAIL)) {
            session()->setFlashMessage('erro', 'Email inválido');
            return redirect()->route('contactos');
        }
        // Checar o numero de telefone

        $result = DB::table('mensagens')->insert(['expediente' => $request->username, 'mail' => $request->email, 'telefone' => $request->telefone, 'mensagem' => $request->mensagem]);

        if ($result) {
            session()->setFlashMessage('success', 'Receberá uma resposta em breve');
            return redirect()->route('contactos');
        }

        session()->setFlashMessage('erro', 'Algo deu errado, tente mais tarde');
        return redirect()->route('contactos');
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