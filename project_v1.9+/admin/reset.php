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

/* --------------------------------------------------------------
 *  Dołączenie wymaganych klas PHPMailer.
 *  Ścieżki są ustawione względem katalogu "admin".
 * -------------------------------------------------------------- */
require __DIR__ . '/../PHPMailer-master/src/Exception.php';
require __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer-master/src/SMTP.php';


/*
 * =====================================================================
 *  Funkcja: PrzypomnijHaslo()
 *
 *  Opis funkcji:
 *      Wysyła wiadomość e-mail z przypomnieniem hasła administratora.
 *      Hasło jest wpisane na stałe ("admin"), co jest zgodne z LAB
 *      oraz uproszczoną wersją panelu CMS w tym projekcie.
 *
 *  Kroki działania:
 *      1) Ustawienie adresu administratora i hasła
 *      2) Konfiguracja PHPMailer do pracy przez SMTP Gmail
 *      3) Zbudowanie wiadomości z hasłem
 *      4) Wysłanie e-maila
 *      5) Wyświetlenie komunikatu o powodzeniu lub błędzie
 * =====================================================================
 */
function PrzypomnijHaslo()
{
    $adminEmail = "romanvoitsiuk@gmail.com"; // adres administratora, na który wyślemy hasło
    $haslo = "admin";                        // aktualne hasło logowania do panelu

    // Tworzymy nowy obiekt PHPMailer umożliwiający wysyłanie wiadomości
    $mail = new PHPMailer(true);

    try {

        /* ----------------------------------------------------------
         *  Konfiguracja SMTP — wysyłanie maili przez serwer Gmail
         * ---------------------------------------------------------- */
        $mail->isSMTP();                         // tryb SMTP
        $mail->Host = 'smtp.gmail.com';          // serwer SMTP Gmail
        $mail->SMTPAuth = true;                  // włączenie autoryzacji SMTP

        // Dane logowania do Gmaila (adres + hasło aplikacji)
        $mail->Username = 'romanvoitsiuk@gmail.com';
        $mail->Password = 'xbeofftvbyrdrxbo';

        $mail->SMTPSecure = 'tls';               // rodzaj szyfrowania
        $mail->Port = 587;                       // port używany do TLS

        /* ----------------------------------------------------------
         *  Tworzenie wiadomości e-mail
         * ---------------------------------------------------------- */
        $mail->setFrom($adminEmail, "Panel administratora"); // nadawca
        $mail->addAddress($adminEmail);                      // odbiorca

        $mail->Subject = 'Przypomnienie hasła';              // temat wiadomości
        $mail->Body = "Twoje hasło administratora to: $haslo"; // treść

        /* ----------------------------------------------------------
         *  Wysyłanie wiadomości
         * ---------------------------------------------------------- */
        $mail->send();
        echo "<h2>Hasło zostało wysłane na adres administratora!</h2>";

    } catch (Exception $e) {

        // Obsługa błędów podczas wysyłania e-maila
        echo "<h2 style='color:red;'>Błąd wysyłania: {$mail->ErrorInfo}</h2>";
    }
}


// =====================================================================
//  NATYCHMIASTOWE URUCHOMIENIE FUNKCJI
//  Po wejściu na stronę reset.php wysyłany jest e-mail z hasłem.
// =====================================================================
PrzypomnijHaslo();


// Link powrotu do strony logowania
echo '<br><a href="login.php">Powrót</a>';
