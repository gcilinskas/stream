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
//        $mail = new PHPMailer(true);
//        $mail->SMTPDebug = 2;
//        //https://myaccount.google.com/security?pli=1#connectedapps -> to enable sending
//        $mail->IsSMTP(); // telling the class to use SMTP
//        $mail->SMTPAuth = true; // enable SMTP authentication
//        $mail->SMTPSecure = 'tls';
//        $mail->Host = "mediaport.lt"; // sets GMAIL as the SMTP server
//        $mail->Port = 587; // set the SMTP port for the GMAIL server
//        $mail->Username = $_ENV["GMAIL_EMAIL"];
//        $mail->Password = $_ENV["GMAIL_PASSWORD"];
//
//        $mail->AddAddress($recipientEmail, $recipientName);
//        $mail->SetFrom('gcilinskas@gmail.com', 'Gediminas');
//        $mail->Subject = $subject;
//        $mail->Body = $body;

        mail($recipientEmail, $subject, $body, "From: admin@mediaport.lt");

//        $mail->Send();
    }
}
