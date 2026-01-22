<?php
/*
 * =====================================================================
 *  Plik: reset.php
 *  Opis:
 *      Moduł służy do przypomnienia hasła administratora panelu CMS.
 *      Po uruchomieniu wysyła wiadomość e-mail z hasłem na adres
 *      administratora za pomocą biblioteki PHPMailer.
 *
 *  Działanie pliku:
 *      - ładuje bibliotekę PHPMailer
 *      - przygotowuje konfigurację SMTP (Gmail)
 *      - tworzy wiadomość e-mail zawierającą hasło administratora
 *      - wysyła wiadomość na zdefiniowany adres
 *      - wyświetla informację o sukcesie lub błędzie
 *
 *  Wymagania LAB 9:
 *      - komentarze dokumentujące działanie funkcji
 *      - opis konfiguracji SMTP i PHPMailer
 * =====================================================================
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require __DIR__ . '/../PHPMailer-master/src/Exception.php';
require __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer-master/src/SMTP.php';



function PrzypomnijHaslo()
{
    $adminEmail = "romanvoitsiuk@gmail.com";
    $haslo = "admin";
    $mail = new PHPMailer(true);

    try {


        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'romanvoitsiuk@gmail.com';
        $mail->Password = 'xbeofftvbyrdrxbo';

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;


        $mail->send();
        echo "<h2>Hasło zostało wysłane na adres administratora!</h2>";

    } catch (Exception $e) {

        echo "<h2 style='color:red;'>Błąd wysyłania: {$mail->ErrorInfo}</h2>";
    }
}


PrzypomnijHaslo();


echo '<br><a href="login.php">Powrót</a>';
