<?php

namespace App\Service;

use App\Entity\User;
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
        $mail = $this->configureMailer();

        $mail->AddAddress($recipientEmail, $recipientName);
        $mail->SetFrom($this->mailerConfig['sender_email'], $this->mailerConfig['sender_name']);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->Send();
    }

    /**
     * @return PHPMailer
     */
    private function configureMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = $this->mailerConfig['debug'];
        $mail->IsSMTP();
        $mail->Host = $this->mailerConfig['host'];
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->Port = $this->mailerConfig['port'];
        $mail->Username = $this->mailerConfig['username'];
        $mail->Password = $this->mailerConfig['password'];

        return $mail;
    }

    /**
     * @param User $user
     *
     * @throws Exception
     */
    public function sendResetPasswordMail(User $user)
    {
        $this->send(
            $user->getEmail(),
            $user->getFirstName(),
            'Slaptazodzio Atstatymas',
            'Jūsų slaptažodis buvo atstatytas. Dabartinis slaptažodis: ' . $user->getPlainPassword()
        );
    }
}
