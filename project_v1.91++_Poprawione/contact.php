<?php
declare(strict_types=1);



use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

class ContactController
{
    public function renderForm(): string
    {
        return '
        <h1>Formularz kontaktowy</h1>
        <form method="post">
            <label>Imię</label><br>
            <input type="text" name="name" required><br><br>

            <label>Email</label><br>
            <input type="email" name="email" required><br><br>

            <label>Wiadomość</label><br>
            <textarea name="message" rows="5" required></textarea><br><br>

            <button type="submit" name="send">Wyślij</button>
        </form>
        ';
    }

    public function handleSend(): ?string
    {
        if (!isset($_POST['send'])) {
            return null;
        }

        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $message = trim((string)($_POST['message'] ?? ''));

        if ($name === '' || $email === '' || $message === '') {
            return "<p style='color:red;'>Uzupełnij wszystkie pola.</p>";
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            // Uwaga: dane SMTP zostały w projekcie wpisane statycznie (jak w wersji pierwotnej).
            $mail->Username = 'romanvoitsiuk@gmail.com';
            $mail->Password = 'xbeofftvbyrdrxbo';

            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('romanvoitsiuk@gmail.com', 'Kontakt – DOTA2fan');
            $mail->addAddress('romanvoitsiuk@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = 'Wiadomość z formularza kontaktowego';
            $mail->Body = "<b>Imię:</b> " . htmlspecialchars($name) . "<br>"
                        . "<b>Email:</b> " . htmlspecialchars($email) . "<br><br>"
                        . "<b>Wiadomość:</b><br>" . nl2br(htmlspecialchars($message));

            $mail->send();
            return "<p style='color:green;'>Wiadomość została wysłana!</p>";
        } catch (Throwable $e) {
            return "<p style='color:red;'>Błąd wysyłki: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}

// Backward compatible functions
function PokazKontakt(): string
{
    return (new ContactController())->renderForm();
}

function WyslijMailKontakt(): void
{
    $msg = (new ContactController())->handleSend();
    if ($msg !== null) {
        echo $msg;
    }
}
