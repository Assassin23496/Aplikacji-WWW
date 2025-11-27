<?php

function PokazKontakt(){
    $wynik = '
        <h2 class="heading">Formularz Kontaktowy</h2>
        <form method="post" action="index.php?id=contact">
            <div class="form-group">
                <label for="email" Twój adres E-mail:</label>
                <input type="email" id="email" name="email" required class="form-control">
            </div>
            <div class="form-group">
                <label for="temat">Temat:</label>
                <input type="text" id="temat" name="temat" required class="form-control">
            </div>
            <div class="form-group">
                <label for="tresc">Treść Wiadomości:</label>
                <textarea id="tresc" name="tresc" rows="8" required class="form-control"></textarea>
            </div>
            <button type="submit" name="kontakt_submit" class="form-submit-btn">Wyślij Wiadomość</button>
        </form>
    ';
    if (isset($_POST['kontakt_submit'])) {
        $odbiorca = 'email@email.com';
        return WyslijMailKontakt($odbiorca);
    }
    return $wynik;
}

function WyslijMailKontakt($odbiorca) {
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
        echo '[nie_wypelniles_pola]';
        return PokazKontakt();
    } else {
        $mail['subject'] = $_POST['temat'];
        $mail['body'] = $_POST['tresc'];
        $mail['sender'] = $_POST['email'];
        $mail['reciptient'] = $odbiorca;

        $header = "From: Formularz kontaktowy <" . $mail['sender'] . ">\n";
        $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
        $header .= "X-Mailer: PRapwww mail 1.2\n";
        $header .= "X-Priority: 3\n";
        $header .= "X-Sender: " . $mail['sender'] . "\n";
        $header .= "Return-Path: <" . $mail['sender'] . ">\n";


        mail($mail['reciptient'], $mail['subject'], $mail['body'], $header);
        echo '[wiadomosc_wyslana]';

    }
}

function PrzypomnijHaslo() {
    global $login, $pass;

    $form_haslo = '
        <h2 class="heading">Przypomnienie Hasła</h2>
        <form method="post" action="index.php?id=forgot_pass">
            <div class="form-group">
                <label for="email_admin">E-mail Konta Administratora:</label>
                <input type="email" id="email_admin" name="email_admin" required class="form-control">
            </div>
            <button type="submit" name="przypomnij_submit" class="form-submit-btn">Wyślij Hasło</button>
        </form>
    ';

    if (isset($_POST['przypomnij_submit'])) {
        $email_admin = $_POST['email_admin'];

        if ($email_admin === $login) {
            $odbiorca = $email_admin;
            $mail['subject'] = 'Przypomnienie hasła do Panelu CMS';
            $mail['body'] = "Twoje hasło do panelu admina to: " . $pass . "";
            $mail['sender'] = 'email@email.com';

            $header = "From: System Przypominania Hasel <" . $mail['sender'] . ">\n";
            $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
            $header .= "X-Mailer: PRapwww mail 1.2\n";
            $header .= "X-Priority: 1\n";
            $header .= "X-Sender: " . $mail['sender'] . "\n";
            $header .= "Return-Path: <" . $mail['sender'] . ">\n";

            if (mail($odbiorca, $mail['subject'], $mail['body'], $header)) {
                return '<p style="color: green;">Hasło zostało wysłane na podany adres e-mail: ' . htmlspecialchars($email_admin) . '.</p>';
            } else {
                return '<p style="color: red;">Błąd wysyłki hasła.</p>';
            }
        } else {
            return '<p style="color: red;">Podany adres e-mail nie jest powiązany z kontem administratora.</p>' . $form_haslo;
        }
    }

    return $form_haslo;
}
?>
