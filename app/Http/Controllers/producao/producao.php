<?php

namespace App\Http\Controllers\producao;

use App\Http\Controllers\Controller;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;
use Sienekib\Mehael\Support\Mailer;

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

    public function load(Request $request)
    {
        $site = '';

        if (file_exists(__delivered_path($request->name))) {
            $site = $request->name;
            return view($request->name . ':site.producao.live', compact('site'));
        }

        return redirect()->route('/', true);
    }

    public function save_leads(Request $request)
    {
        // Personalizar os nomes, isto é: 
        // username = first + last
        // usermail = 
        // telefone 
        // message
        /*
        2024032116460d7a6a276fbee80b3e7b4a31d2ccd642.jpg
        1 1 1 script
        707a0267-916a-496f-9df8-c62763319cdf, 2133_moso_interior
        */

        if ($_SERVER['HTTP_REFERER']) {
            $domain = explode('/', $_SERVER['HTTP_REFERER']);
            $domain = end($domain);

            $account = DB::table('temp_parceiros')->where('dominio', '=', $domain)->select('parceiro_id')->get()[0];

            if (is_null($request->first) || is_null($request->last) || is_null($request->email) || is_null($request->telefone) || is_null($request->message)) {
                $this->send_alert_message('Por favor preencha todos os campos');
                return redirect()->back();
            }

            $lastInserted = DB::table('leads')->insertId([
                'conta_id' => $account->parceiro_id,
                'username' => $request->first . ' ' . $request->last,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'mensagem' => $request->message
            ]);

            if ($lastInserted) {
                $this->send_alert_message('Mensagem enviada com sucesso. Em breve receberá a sua resposta!');
                return redirect()->back();
            }

            $this->send_alert_message('Algo deu errado ao enviar a mensagem, tente mais tarde');
            return redirect()->back();
        }

        $this->send_alert_message('Algo deu errado ao enviar a mensagem, tente mais tarde');
        return redirect()->back();
    }

    public function resposta_lead(Request $request)
    {
        // Aqui você pode processar os dados recebidos do formulário

        // Enviar e-mail de resposta
        $assunto = 'Resposta da plataforma';

        if (Mailer::send($assunto, $request->mensagem, $request->client_mail)) {
            return response()->json(['message' => 'E-mail enviado com sucesso!']);
        } else {
            return response()->json(['message' => 'Erro ao enviar o e-mail'], 500);
        }
    }

    private function send_alert_message($text)
    {
        echo '<script>';
        echo "alert('{$text}');";
        echo '</script>';
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
