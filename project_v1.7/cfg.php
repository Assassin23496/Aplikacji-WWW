<?php
session_start();

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza   = 'moja_strona';

$link = mysqli_connect($dbhost, $dbuser, $dbpass);

if (!$link) {
    die("Błąd połączenia z MySQL: " . mysqli_connect_error());
}

if (!mysqli_select_db($link, $baza)) {
    $create_db = "CREATE DATABASE IF NOT EXISTS $baza CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci";
    if (mysqli_query($link, $create_db)) {
        mysqli_select_db($link, $baza);

        // Utwórz tabele po utworzeniu bazy
        include_once "create_tables.php";
        createDatabaseTables($link);   // ← MUSISZ WYWOŁAĆ FUNKCJĘ
    }
}


// Ustawienie kodowania znaków
mysqli_set_charset($link, "utf8mb4");

// LOGIN DO PANELU ADMINA
$login = "admin@root.com";
$pass  = "!@#QWEasdzxc";

?>
