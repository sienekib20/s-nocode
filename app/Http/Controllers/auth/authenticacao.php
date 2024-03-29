<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;
use Sienekib\Mehael\Support\Auth;
use Sienekib\Mehael\Support\Hash;

class authenticacao extends Controller
{

    public function login()
    {
        session()->setFlashMessage('__load', true);
        return redirect()->route('/');
        //return view('entrar:site.silica.user-login');
    }

    public function autenticar(Request $request)
    {
        if (Auth::attempt($request->username, $request->password)) {
            if (session()->has('__redirect_url')) {
                $redirect_url = session()->get('__redirect_url');
                session()->remove('__redirect_url');
                return redirect()->route($redirect_url);
            }
            $user = DB::table('contas')->select('uuid')->where('conta_id', '=', Auth::user()->id)->get()[0];
            $uuid = explode('-', $user->uuid)[3];
            
            return response()->json(['response' => 1, 'href' => "/user/{$uuid}/view"]);
        }
        return response()->json(['response' => 'Credenciais inválidas']);
    }

    public function register()
    {
        return view('register:site.auth.register');
    }

    public function user_create(Request $request)
    {
        // Verifica a correspondência de senhas
        if ($request->password !== $request->re_password) {
            return response()->json(['response' => 'A senha não corresponde']);
        }

        // Verifica se dado campo está vazio
        if ($this->empty([$request->nome, $request->password, $request->telefone, $request->email, $request->re_password, $request->password])) {
            return response()->json(['response' => 'Por favor, Preencha todos os campos!']);
        }

        $pwd = Hash::encrypt($request->password);

        $existing = DB::table('contas')->select('*')->where('nome', '=', $request->nome)->orwhere('email', '=', $request->email)->get();

        // Verifica se dado usuário existe
        if (!empty($existing)) {
            return response()->json(['response' => 'Este usuário já existe']);
        }
        $nome_apelido = explode(' ', $request->nome);
        $id = DB::table('contas')->insertId(['uuid' => Uuid::uuid4(), 'nome' => $nome_apelido[0], 'apelido' => $nome_apelido[1] ?? '', 'telefone' => $request->telefone, 'email' => $request->email, 'senha' => $pwd, 'tipo_conta_id' => 1]);

        if ($id != 0) {
            DB::table('parceiros')->insert([
                'conta_id' => $id
            ]);
            return response()->json(['response' => 1]);
        }

        return response()->json(['response' => 'Algo deu errado, tente mais tarde']);
    }

    private function empty($arr)
    {
        $arr = is_array($arr) ? $arr : [$arr];
        foreach ($arr as $item) {
            if (strlen($item) == 0) {
                return true;
            }
        }
    }

    public function destroy()
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('/');
        }
        return redirect()->route('/');
    }
}