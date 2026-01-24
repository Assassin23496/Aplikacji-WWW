<?php
declare(strict_types=1);



require_once __DIR__ . "/../cfg.php";

// PHPMailer
require __DIR__ . '/../PHPMailer-master/src/Exception.php';
require __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer-master/src/SMTP.php';

$service = new MailService();

try {
    $service->sendPasswordReminder([
        'host' => 'smtp.gmail.com',
        'username' => 'romanvoitsiuk@gmail.com',
        'password' => 'xbeofftvbyrdrxbo',
        'secure' => 'tls',
        'port' => 587,
        'from_email' => 'romanvoitsiuk@gmail.com',
        'from_name' => 'CMS Admin',
        'to_email' => 'romanvoitsiuk@gmail.com',
        'subject' => 'Przypomnienie hasła',
        'body' => 'hasło do panelu administratora: <b>admin</b>',
    ]);

    echo "<h2>Hasło zostało wysłane na adres administratora!</h2>";
} catch (Throwable $e) {
    echo "<h2 style='color:red;'>Błąd wysyłki: " . htmlspecialchars($e->getMessage()) . "</h2>";
}
