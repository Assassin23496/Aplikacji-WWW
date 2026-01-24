<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    /**
     * Minimal wrapper to send an email via PHPMailer.
     * PHPMailer library folder is intentionally kept untouched.
     */
    public function sendPasswordReminder(array $cfg): void
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = (string)$cfg['host'];
        $mail->SMTPAuth = true;
        $mail->Username = (string)$cfg['username'];
        $mail->Password = (string)$cfg['password'];
        $mail->SMTPSecure = (string)$cfg['secure'];
        $mail->Port = (int)$cfg['port'];

        $mail->setFrom((string)$cfg['from_email'], (string)($cfg['from_name'] ?? 'Admin'));
        $mail->addAddress((string)$cfg['to_email']);

        $mail->isHTML(true);
        $mail->Subject = (string)$cfg['subject'];
        $mail->Body = (string)$cfg['body'];

        $mail->send();
    }
}
