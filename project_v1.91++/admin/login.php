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

session_start();
include("../cfg.php");



if (isset($_POST['login'])) {

    $login = $_POST['login'];
    $pass  = $_POST['pass'];


    if ($login == "admin" && $pass == "admin") {

        $_SESSION['logged_in'] = true;

        header("Location: admin.php");
        exit();

    } else {

        echo "<p style='color:red;'>Błędne dane logowania!</p>";
    }
}
?>



<h2>Logowanie do panelu</h2>
<head>
    <link rel="stylesheet" href="admin.css">
    <title></title>
</head>
<form method="post">
    <input type="text" name="login" placeholder="Login"><br><br>
    <input type="password" name="pass" placeholder="Hasło"><br><br>
    <button type="submit">Zaloguj</button>
</form>

<br>

<a href="reset.php">Przypomnij hasło</a>
