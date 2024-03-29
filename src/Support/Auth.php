<?php

declare(strict_types=1);

namespace Sienekib\Mehael\Support;

use Sienekib\Mehael\Database\Factory\DB;

class Auth
{

    public static function attempt($username, $password)
    {
        $data = DB::table('contas')->select('*')->where('nome', '=', $username)->orWhere('email', '=', $username)->get();
        if (!empty($data)) {
            if (password_verify($password, $data[0]->senha)) {
                //$columns = DB::raw('SHOW COLUMNS FROM contas');
                $user = (object) [
                    'id' => $data[0]->conta_id,
                    //'uuid' => $data[0]->uuid,
                    'nome' => $data[0]->nome,
                    'email' => $data[0]->email,
                ];
                Session::set('user', $user);
                Session::regenerateId();
                return true;
            }

            Session::setFlashMessage('erro', 'Senha inválida');
            return false;
        }

        Session::setFlashMessage('erro', 'Usuário desconhecido');
        return false;
    }

    public static function check()
    {
        return Session::has('user');
    }

    public static function user()
    {
        //dd(Session::get('users'));
        return Session::get('user');
    }

    public static function logout()
    {
        session_destroy();
        Session::remove('user');
    }
}

// Exemplo de uso:

// Inicializar a sessão
/*session_start();

// Autenticar um usuário
Auth::attempt('user1', 'secret');

// Verificar se o usuário está autenticado
if (Auth::check()) {
    echo 'Usuário autenticado: ' . Auth::user()['username'];
} else {
    echo 'Usuário não autenticado';
}

// Desautenticar o usuário (logout)
Auth::logout();*/