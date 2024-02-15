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
        //return view('entrar:site.auth.login');
        return view('entrar:site.silica.user-login');
    }

    public function autenticar(Request $request)
    {
        if (Auth::attempt($request->username, $request->password)) {
            session()->setFlashMessage('Sucesso', 'Seja bem vindo');
            if (session()->has('__redirect_url')) {
                $redirect_url = session()->get('__redirect_url');
                session()->remove('__redirect_url');
                return redirect()->route($redirect_url);
            }
            return redirect()->route('/');
        }
        session()->setFlashMessage('erro', 'Dados inválidos');
        return redirect()->route('entrar');
    }

    public function register()
    {
        return view('register:site.auth.register');
    }

    public function user_create(Request $request)
    {
        // Verifica a correspondência de senhas
        if ($request->password !== $request->re_password) {
            session()->setFlashMessage('erro', 'senha não corresponde');
            return redirect()->route('register');
        }

        // Verifica se dado campo está vazio
        if ($this->empty([$request->nome, $request->password, $request->telefone, $request->email, $request->re_password, $request->password])) {
            session()->setFlashMessage('erro', 'Campos obrigatórios');
            return redirect()->route('register');
        }

        $pwd = Hash::encrypt($request->password);

        $existing = DB::table('contas')->select('*')->where('nome', '=', $request->nome)->orwhere('email', '=', $request->email)->get();

        // Verifica se dado usuário existe
        if (!empty($existing)) {
            session()->setFlashMessage('erro', 'Este nome ou email já foi usado');
            return redirect()->route('register');
        }

        $id = DB::table('contas')->insertId(['uuid' => Uuid::uuid4(), 'nome' => $request->nome, 'apelido' => $request->apelido, 'telefone' => $request->telefone, 'email' => $request->email, 'senha' => $pwd, 'tipo_conta_id' => 1]);

        if ($id != 0) {
            DB::table('parceiros')->insert([
                'conta_id' => $id
            ]);
            session()->setFlashMessage('success', 'Cadastro feito com sucesso');
            return redirect()->route('entrar');
        }

        session()->setFlashMessage('erro', 'Algo deu errado, tente mais tarde');
        return redirect()->route('register');
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
