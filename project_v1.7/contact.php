<?php
// contact.php
// v1.7 - Laboratorium nr 8

class Contact {
    public function PokazKontakt($typ = 'kontakt') {
        $form = '';

        if ($typ == 'kontakt') {
            $form = '
            <div class="contact-form">
                <h2>Formularz kontaktowy</h2>
                <form action="" method="POST" class="contact-form-style">
                    <input type="hidden" name="action" value="wyslij_kontakt">

                    <div class="form-group">
                        <label for="imie">Imię i nazwisko *</label>
                        <input type="text" id="imie" name="imie" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="email">Adres email *</label>
                        <input type="email" id="email" name="email" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="temat">Temat wiadomości *</label>
                        <input type="text" id="temat" name="temat" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="tresc">Treść wiadomości *</label>
                        <textarea id="tresc" name="tresc" rows="6" required class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn-submit">Wyślij wiadomość</button>
                    </div>
                </form>
            </div>';
        }
        elseif ($typ == 'przypomnij_haslo') {
            $form = '
            <div class="password-reminder">
                <h2>Przypomnij hasło</h2>
                <form action="" method="POST" class="contact-form-style">
                    <input type="hidden" name="action" value="przypomnij_haslo">

                    <div class="form-group">
                        <label for="admin_email">Email administratora *</label>
                        <input type="email" id="admin_email" name="admin_email" required class="form-control">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn-submit">Przypomnij hasło</button>
                    </div>
                </form>
            </div>';
        }

        return $form;
    }

    /**
     * Zadanie 1 & 4: Metoda WyslijMailKontakt()
     */
    public function WyslijMailKontakt($dane) {
        $imie = $dane['imie'] ?? '';
        $email = $dane['email'] ?? '';
        $temat = $dane['temat'] ?? '';
        $tresc = $dane['tresc'] ?? '';

        // Walidacja danych
        if (empty($imie) || empty($email) || empty($temat) || empty($tresc)) {
            return array('status' => false, 'message' => 'Wszystkie pola są wymagane.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array('status' => false, 'message' => 'Nieprawidłowy adres email.');
        }

        // Nagłówki wiadomości
        $headers = "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Adres odbiorcy
        $to = "admin@bandwebsite.com";

        // Treść wiadomości
        $wiadomosc = "Nowa wiadomość kontaktowa ze strony:\n\n";
        $wiadomosc .= "Imię i nazwisko: " . $imie . "\n";
        $wiadomosc .= "Email: " . $email . "\n";
        $wiadomosc .= "Temat: " . $temat . "\n";
        $wiadomosc .= "Treść:\n" . $tresc . "\n";
        $wiadomosc .= "\nWiadomość wysłana: " . date('Y-m-d H:i:s');

        // Wysyłanie maila
        if (mail($to, $temat, $wiadomosc, $headers)) {
            return array('status' => true, 'message' => 'Wiadomość została wysłana pomyślnie.');
        } else {
            return array('status' => false, 'message' => 'Błąd podczas wysyłania wiadomości.');
        }
    }

    /**
     * Zadanie 1 & 3: Metoda PrzypomnijHaslo()
     */
    public function PrzypomnijHaslo($email) {
        // Walidacja emaila
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array('status' => false, 'message' => 'Nieprawidłowy adres email.');
        }

        // W tej uproszczonej wersji symulujemy sprawdzenie w bazie danych
        // W rzeczywistej aplikacji tutaj byłoby połączenie z bazą danych

        // Nagłówki wiadomości
        $headers = "From: system@bandwebsite.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        $to = $email;
        $temat = "Przypomnienie hasła - Band Website v1.7";

        // Treść wiadomości (OSTRZEŻENIE: to jest uproszczona, niebezpieczna wersja!)
        $wiadomosc = "Przypomnienie danych dostępu:\n\n";
        $wiadomosc .= "Email: " . $email . "\n";
        $wiadomosc .= "\nUWAGA: Ta funkcja jest w wersji deweloperskiej.\n";
        $wiadomosc .= "W rzeczywistej aplikacji należy zaimplementować bezpieczny system resetowania hasła.\n";
        $wiadomosc .= "Wiadomość wygenerowana: " . date('Y-m-d H:i:s');

        // Wysyłanie maila
        if (mail($to, $temat, $wiadomosc, $headers)) {
            return array('status' => true, 'message' => 'Email z przypomnieniem został wysłany.');
        } else {
            return array('status' => false, 'message' => 'Błąd podczas wysyłania emaila z przypomnieniem.');
        }
    }

    /**
     * Metoda do obsługi przesyłania formularzy
     */
    public function ProcessForm() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action == 'wyslij_kontakt') {
                $result = $this->WyslijMailKontakt($_POST);
                $this->ShowMessage($result['message'], $result['status']);

            } elseif ($action == 'przypomnij_haslo') {
                $email = $_POST['admin_email'] ?? '';
                $result = $this->PrzypomnijHaslo($email);
                $this->ShowMessage($result['message'], $result['status']);
            }
        }
    }

    /**
     * Metoda pomocnicza do wyświetlania komunikatów
     */
    private function ShowMessage($message, $isSuccess = true) {
        $class = $isSuccess ? 'alert-success' : 'alert-error';
        echo '<div class="alert ' . $class . '">' . htmlspecialchars($message) . '</div>';
    }
}

// Inicjalizacja klasy
$contact = new Contact();

// Obsługa formularza
$contact->ProcessForm();

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt - Band Website v1.7</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        /* TIP 1: Stylowanie formularza - dodatkowe style */
        .contact-form-style {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-weight: bold;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .password-reminder {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        .contact-form h2, .password-reminder h2 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Band Website v1.7 - Kontakt</h1>
        <nav>
            <a href="index.php">Strona główna</a> |
            <a href="filmy.html">Filmy</a> |
            <a href="contact.php">Kontakt</a> |
            <a href="admin.php">Panel admina</a>
        </nav>
    </header>

    <main>
        <?php
        // Wyświetlenie formularza kontaktowego
        echo $contact->PokazKontakt('kontakt');

        // Wyświetlenie formularza przypomnienia hasła
        echo $contact->PokazKontakt('przypomnij_haslo');
        ?>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Band Website. Wersja 1.7 - Laboratorium nr 8</p>
    </footer>
</div>
</body>
</html>
