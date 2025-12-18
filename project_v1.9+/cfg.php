<?php
/*
 * ======================================================================
 *  Plik: cfg.php
 *  Cel pliku:
 *      Zawiera wszystkie informacje potrzebne do nawiązania połączenia
 *      z bazą danych MySQL. Jest to podstawowy moduł konfiguracyjny
 *      całego CMS — bez niego żaden moduł korzystający z bazy nie działa.
 *
 *  Powód umieszczenia osobno:
 *      - łatwa modyfikacja danych logowania
 *      - unikanie duplikacji kodu w wielu plikach PHP
 *      - zgodnie z zasadami projektowymi (separacja logiki i konfiguracji)
 *      - wymaganie LAB 9 dotyczące struktury CMS
 *
 *  Ten plik NIE MOŻE zawierać żadnej logiki wyświetlania!
 * ======================================================================
 */


// ======================================================================
//  Dane dostępowe do lokalnej bazy danych MySQL (XAMPP).
//  W środowisku XAMPP te ustawienia są standardowe:
//      host: localhost
//      użytkownik: root
//      hasło: (puste)
// ======================================================================
$db_host = "localhost";   // adres serwera MySQL
$db_user = "root";        // domyślny użytkownik
$db_pass = "";            // domyślne brak hasła w XAMPP
$db_name = "moja_strona"; // nazwa bazy danych używanej w projekcie


// ======================================================================
//  Próba nawiązania połączenia z bazą danych.
//  Funkcja mysqli_connect() zwraca uchwyt połączenia lub FALSE.
//  Zmienna $link jest później używana globalnie w innych plikach.
// ======================================================================
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);


// ======================================================================
//  Sprawdzenie, czy połączenie zostało utworzone poprawnie.
//  Jeśli nie — kończymy działanie skryptu i wyświetlamy błąd.
//
//  Funkcja die() zatrzymuje wykonywanie PHP — w tym przypadku
//  jest to bezpieczne, bo dalsze działanie bez bazy i tak byłoby
//  niemożliwe (CMS przechowuje treści w MySQL).
// ======================================================================
if (!$link) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}


// ======================================================================
//  Ustawienie kodowania znaków na UTF-8.
//  Dzięki temu polskie znaki (ą, ę, ł, ó...) są poprawnie
//  odczytywane z bazy i wyświetlane na stronie.
// ======================================================================
mysqli_set_charset($link, "utf8");

?>
