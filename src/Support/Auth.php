<?php

declare(strict_types=1);

namespace Sienekib\Mehael\Support;

use Sienekib\Mehael\Database\Factory\DB;

class Auth
{
    protected static $users = [
        // Exemplo de dados de usuário (substitua com seus próprios dados)
        [
            'id' => 1,
            'username' => 'user1',
            'email' => 'user1@example.com',
            'password' => '$2y$10$YarQQX6RLmKlGxib0/QSsOpHZuWDzECVzYFmU7cakl9vH4jnHYtza', // senha: secret
        ],
        [
            'id' => 2,
            'username' => 'user2',
            'email' => 'user2@example.com',
            'password' => '$2y$10$YarQQX6RLmKlGxib0/QSsOpHZuWDzECVzYFmU7cakl9vH4jnHYtza', // senha: secret
        ],
        // Adicione mais usuários conforme necessário
    ];

    public static function attempt($username, $password)
    {
        $data = DB::table('contas')->select('*')->where('nome', '=', $username)->orWhere('email', '=', $username)->get();

        if (!empty($data)) {
            if (password_verify($password, $data[0]->senha)) {
                $user = (object) [
                    'id' => $data[0]->conta_id,
                    'username' => $data[0]->nome,
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

        return Session::get('user');
    }

    public static function logout()
    {
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
