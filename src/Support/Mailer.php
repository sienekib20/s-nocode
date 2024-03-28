<?php

namespace Sienekib\Mehael\Support;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function send($assunto, $mensagem, $emailDestino)
    {
        // Instanciar o PHPMailer
        $mail = new PHPMailer(true); // Passando 'true' habilita exceções

        try {
            // Configuração SMTP
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->Port = env('MAIL_PORT');

            // Se precisar de autenticação
            if (env('MAIL_USERNAME') && env('MAIL_PASSWORD')) {
                $mail->SMTPAuth = true;
                $mail->Username = env('MAIL_USERNAME');
                $mail->Password = env('MAIL_PASSWORD');
            }

            // Outras configurações
            $mail->SMTPSecure = env('MAIL_ENCRYPTION') ?: false;
            $mail->setFrom(env('MAIL_FROM_ADDRESS') ?: 'seu_email@example.com', env('MAIL_FROM_NAME') ?: 'Seu Nome');
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
}
