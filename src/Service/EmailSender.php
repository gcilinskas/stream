<?php

namespace App\Service;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class EmailSender
 */
class EmailSender
{
    /**
     * @var array
     */
    private $mailerConfig;

    /**
     * EmailSender constructor.
     *
     * @param array $mailerConfig
     */
    public function __construct(array $mailerConfig)
    {
        $this->mailerConfig = $mailerConfig;
    }

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
            //https://myaccount.google.com/security?pli=1#connectedapps -> to enable sending if gmail
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host = $this->mailerConfig['host'];
            $mail->SMTPAuth = true; // enable SMTP authentication
            $mail->SMTPSecure = "ssl"; // sets the prefix to the server

            $mail->Port = $this->mailerConfig['port']; // TCP port to connect to
            $mail->Username = $this->mailerConfig['username'];
            $mail->Password = $this->mailerConfig['password'];

            $mail->AddAddress($recipientEmail, $recipientName);
            $mail->SetFrom($this->mailerConfig['sender_email'], $this->mailerConfig['sender_name']);
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
