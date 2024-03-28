<?php

namespace Sienekib\Mehael\Support;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{

    public static function verifyDomainsMail($email)
    {
        $mailparts = explode('@', $email);
        $domain = isset($mailparts[1]) ? $mailparts[1] : 'exists.ao';
        $ip = gethostbyname($domain);
        if ($ip == $domain) {
            return false;
        } else {
            return true;
        }
    }

    public static function send($subject, $mailclient, $nameClient, $content, $mailuser, $pass, $nameUser)
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.zoho.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $mailuser; #suporteDev#24
            $mail->Password   = $pass;
            $mail->SMTPSecure = 'ssl'; // ou tls
            $mail->Port       = 465;   // ou 587
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64'; // ou 'quoted-printable'
            $mail->setFrom($mailuser, $nameUser);
            $mail->Subject = $subject;
            $mail->addAddress($mailclient, $nameClient);
            $mail->isHTML(true);
            if (self::verifyDomainsMail($mailclient)) {
                $mail->Body = $content;
                $mail->send();
                return true;
            }
        } catch (Exception $e) {
            echo ($mail->ErrorInfo);
            return false;
        }
    }
    /*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir o autoloader do Composer
require 'caminho/para/vendor/autoload.php';

// Função para enviar e-mail
function enviarEmail($assunto, $mensagem, $emailDestino) {
    // Instanciar o PHPMailer
    $mail = new PHPMailer(true); // Passando 'true' habilita exceções

    try {
        // Configuração SMTP
        $mail->isSMTP();
        $mail->Host = getenv('MAIL_HOST');
        $mail->Port = getenv('MAIL_PORT');
        
        // Se precisar de autenticação
        if (getenv('MAIL_USERNAME') && getenv('MAIL_PASSWORD')) {
            $mail->SMTPAuth = true;
            $mail->Username = getenv('MAIL_USERNAME');
            $mail->Password = getenv('MAIL_PASSWORD');
        }

        // Outras configurações
        $mail->SMTPSecure = getenv('MAIL_ENCRYPTION') ?: false;
        $mail->setFrom(getenv('MAIL_FROM_ADDRESS') ?: 'seu_email@example.com', getenv('MAIL_FROM_NAME') ?: 'Seu Nome');
        $mail->addAddress($emailDestino);

        // Conteúdo do e-mail
        $mail->isHTML(true); // Se o conteúdo for HTML
        $mail->Subject = $assunto;
        $mail->Body = $mensagem;

        // Enviar e verificar se foi enviado com sucesso
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Em caso de erro, retorna falso
        return false;
    }
}

// Função resposta_lead
function resposta_lead(Request $request) {
    // Aqui você pode processar os dados recebidos do formulário

    // Enviar e-mail de resposta
    $assunto = 'Assunto do e-mail';
    $mensagem = 'Corpo do e-mail';
    $emailDestino = 'destinatario@example.com';

    if (enviarEmail($assunto, $mensagem, $emailDestino)) {
        return response()->json(['message' => 'E-mail enviado com sucesso!']);
    } else {
        return response()->json(['message' => 'Erro ao enviar o e-mail'], 500);
    }
}

// Chamar a função resposta_lead quando a rota for acionada
resposta_lead($request);
*/
}
