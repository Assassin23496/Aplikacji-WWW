<?php
/*
 * =====================================================================
 *  Plik: login.php
 *  Opis:
 *      Formularz logowania do panelu administratora CMS.
 *      Weryfikacja odbywa się lokalnie — login i hasło są wpisane
 *      bezpośrednio w kodzie (wersja projektowa).
 *
 *  Działanie:
 *      - przyjmowanie danych z formularza (POST)
 *      - sprawdzanie poprawności loginu i hasła
 *      - ustawianie zmiennej sesyjnej po poprawnym logowaniu
 *      - przekierowanie do pliku admin.php
 *
 *  Wymagania LAB 9:
 *      - komentarze dokumentujące działanie modułu
 *      - opis działania sesji i walidacji danych
 * =====================================================================
 */

session_start();        // Uruchomienie sesji – potrzebne do zapisywania stanu logowania
include("../cfg.php");  // Połączenie z bazą (tu nieużywane, ale wymagane strukturą projektu)


// =====================================================================
//  Obsługa logowania
//  Sprawdzamy, czy formularz został wysłany — jeśli tak, pobieramy dane
//  i porównujemy z wartością wpisaną w kodzie (admin/admin).
// =====================================================================
if (isset($_POST['login'])) {

    $login = $_POST['login'];   // login wprowadzony w formularzu
    $pass  = $_POST['pass'];    // hasło wprowadzone w formularzu

    // --------------------------------------------------------------
    //  Weryfikacja danych logowania.
    //  W projekcie dane są zakodowane na stałe, ponieważ:
    //      - tak przewidziano w LAB
    //      - brak tu bazy użytkowników
    // --------------------------------------------------------------
    if ($login == "admin" && $pass == "admin") {

        // Ustawienie sesji informującej, że użytkownik jest zalogowany
        $_SESSION['logged_in'] = true;

        // Po poprawnym logowaniu przekierowanie do panelu admina
        header("Location: admin.php");
        exit();

    } else {

        // Komunikat o błędnych danych
        echo "<p style='color:red;'>Błędne dane logowania!</p>";
    }
}
?>

<!-- ===================================================================
     FORMULARZ LOGOWANIA
     Użytkownik podaje login i hasło, dane wysyłane są metodą POST
     =================================================================== -->

<h2>Logowanie do panelu</h2>

<form method="post">
    <input type="text" name="login" placeholder="Login"><br><br>
    <input type="password" name="pass" placeholder="Hasło"><br><br>
    <button type="submit">Zaloguj</button>
</form>

<br>

<!-- Link do formularza przypominania hasła -->
<a href="reset.php">Przypomnij hasło</a>
