<?php
/*
 * ======================================================================
 *  Plik: contact.php
 *  Projekt: CMS – formularz kontaktowy z wysyłką wiadomości przez SMTP
 *
 *  Opis funkcjonalny:
 *  - PokazKontakt()    → wyświetla formularz kontaktowy użytkownikowi
 *  - WyslijMailKontakt() → obsługuje wysyłanie maila poprzez PHPMailer
 *
 *  Ten moduł NIE korzysta z bazy danych — działa niezależnie od CMS.
 *  Wysyłanie wiadomości odbywa się za pomocą serwera SMTP Gmail.
 *
 *  Plik zawiera komentarze zgodne z wymaganiami LAB 9.
 * ======================================================================
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* --------------------------------------------------------------
 *  Wczytanie klas biblioteki PHPMailer
 *  Biblioteka ta umożliwia wysyłanie wiadomości SMTP
 *  bez konieczności konfigurowania serwera mail() na XAMPP.
 * -------------------------------------------------------------- */
require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';


/*
 * ======================================================================
 *  Funkcja: PokazKontakt()
 *  Opis:
 *      Generuje i zwraca kod HTML formularza kontaktowego.
 *      Formularz wysyła dane metodą POST do tej samej strony.
 *
 *  Dane zbierane z formularza:
 *      - Imię użytkownika
 *      - Adres e-mail
 *      - Treść wiadomości
 *
 *  Zwracana wartość:
 *      HTML formularza kontaktowego
 * ======================================================================
 */
function PokazKontakt()
{
    return '
    <h1>Formularz kontaktowy</h1>
    <form method="post">
        <label>Imię</label><br>
        <input type="text" name="name"><br><br>

        <label>Email</label><br>
        <input type="email" name="email"><br><br>

        <label>Wiadomość</label><br>
        <textarea name="message" rows="5"></textarea><br><br>

        <button type="submit" name="send">Wyślij</button>
    </form>
    ';
}


/*
 * ======================================================================
 *  Funkcja: WyslijMailKontakt()
 *  Opis:
 *      Funkcja odpowiada za wysłanie wiadomości e-mail
 *      w odpowiedzi na dane przesłane z formularza kontaktowego.
 *
 *  Działanie:
 *      1) Sprawdzenie, czy formularz został wysłany
 *      2) Konfiguracja PHPMailer i serwera SMTP Gmail
 *      3) Utworzenie wiadomości e-mail (nadawca, odbiorca, treść)
 *      4) Próba wysłania wiadomości
 *      5) Obsługa wyjątków i wyświetlanie komunikatów
 *
 *  Uwagi:
 *      - Wysyłanie maili przez Gmail wymaga hasła aplikacji
 *      - PHPMailer zapobiega wielu błędom typowym dla funkcji mail()
 * ======================================================================
 */
function WyslijMailKontakt()
{
    // Jeżeli formularz nie został wysłany → zakończ funkcję
    if (!isset($_POST['send'])) return;

    // Tworzymy nowy obiekt klasy PHPMailer
    $mail = new PHPMailer(true);

    try {
        /* ----------------------------------------------------------
         *  KONFIGURACJA SMTP — dane do logowania na serwer Gmail
         * ---------------------------------------------------------- */
        $mail->isSMTP();                        // tryb wysyłki przez SMTP
        $mail->Host = 'smtp.gmail.com';         // adres serwera SMTP Gmail
        $mail->SMTPAuth = true;                 // włączenie autoryzacji SMTP

        // Dane uwierzytelniające — adres Gmail + hasło aplikacji
        $mail->Username = 'romanvoitsiuk@gmail.com';
        $mail->Password = 'xbeofftvbyrdrxbo';   // hasło aplikacji (16 znaków)

        $mail->SMTPSecure = 'tls';              // typ szyfrowania
        $mail->Port = 587;                      // port SMTP dla TLS

        /* ----------------------------------------------------------
         *  KONFIGURACJA WIADOMOŚCI
         * ---------------------------------------------------------- */

        // Ustawienie nadawcy — dane podane w formularzu
        $mail->setFrom($_POST['email'], $_POST['name']);

        // Adres odbiorcy — Twój adres administratora
        $mail->addAddress('romanvoitsiuk@gmail.com');

        // Temat wiadomości
        $mail->Subject = 'Wiadomość z formularza kontaktowego';

        // Treść wiadomości budowana dynamicznie z danych POST
        $mail->Body =
            "Imię: " . $_POST['name'] . "\n" .
            "Email: " . $_POST['email'] . "\n\n" .
            $_POST['message'];

        /* ----------------------------------------------------------
         *  PRÓBA WYSŁANIA WIADOMOŚCI
         * ---------------------------------------------------------- */
        $mail->send();

        // Komunikat po udanym wysłaniu
        echo "<h2 style='color:green;'>Wiadomość wysłana!</h2>";

    } catch (Exception $e) {

        // Komunikat o błędzie wraz z informacją z PHPMailer
        echo "<h2 style='color:red;'>Błąd wysyłania: {$mail->ErrorInfo}</h2>";
    }
}
?>
