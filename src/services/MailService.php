<?php

namespace App\services;

use App\config\DotEnv;
use PHPMailer\PHPMailer\PHPMailer;


(new DotEnv(__DIR__ . '/../../.env'))->load();


class MailService
{
    public $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host       = getenv("MAIL_HOST");
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = getenv("MAIL_USER");
        $this->mail->Password   = getenv("MAIL_PASS");
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = getenv("MAIL_PORT");
        $this->mail->setFrom(getenv("MAIL_SENDER"), 'Confidebat');
        $this->mail->isHTML(true);
    }

    /**
     * send mail
     *
     * @param string $recipient
     * @param string $subject
     * @param string $body
     * @return void
     */
    public static function sendMail($recipient, $subject, $body)
    {
        try {
            self::$mail->addAddress($recipient);
            self::$mail->Subject = $subject;
            self::$mail->Body    = $body;

            return self::$mail->send();
        } catch (\Exception $error) {
            echo $error->getMessage();
        }
    }
}
