<?php

namespace App\Service;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EmailSender
{
    /**
     * @param string $recipientEmail
     * @param string $recipientName
     * @param string $subject
     * @param string $body
     *
     * @throws Exception
     */
    public function send(string $recipientEmail, string $recipientName, string $subject, string $body)
    {
        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 2;
            //https://myaccount.google.com/security?pli=1#connectedapps -> to enable sending
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host = "smtp.pingvinas.serveriai.lt"; // sets GMAIL as the SMTP server
            $mail->SMTPAuth = true; // enable SMTP authentication
            $mail->SMTPSecure = "ssl"; // sets the prefix to the servier

            $mail->Port = 465; // TCP port to connect to
            $mail->Username = 'info@mediaport.lt';
            $mail->Password = $_ENV['MAIL_PASSWORD'];

            $mail->AddAddress($recipientEmail, $recipientName);
            $mail->SetFrom('info@mediaport.lt', 'Gediminas');
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->Send();
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            die();
        }

    }
}
