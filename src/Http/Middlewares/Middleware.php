<?php

namespace Sienekib\Mehael\Http\Middlewares;

use Sienekib\Mehael\Support\Session;
use Sienekib\Mehael\Http\Request;

class Middleware {

        public function execute(string $check, Request $request)
        {
                $restricted = Session::has($check);

                if (!$restricted) {
                        // Armazena a URL atual para redirecionamento posterior
                        Session::set('redirect_url', $request->uri());
                        // Redireciona o usuário para a página de login
                        header('Location: /login');
                        exit();
                }
        }

}
    