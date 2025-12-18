<?php
/*
 * =====================================================================
 *  Plik: admin.php
 *  Opis:
 *      Główny panel administratora umożliwiający:
 *          - przeglądanie listy podstron z bazy danych
 *          - edytowanie wybranej podstrony
 *          - usuwanie stron z bazy
 *
 *  Dostęp:
 *      Plik chroniony jest sesją – dostęp tylko po zalogowaniu.
 *      Jeżeli użytkownik nie jest zalogowany → przekierowanie
 *      do login.php.
 *
 *  Wymagania LAB 9:
 *      - komentarze dokumentacyjne
 *      - komentarze przy operacjach na bazie
 *      - opis działania modułu administracyjnego
 * =====================================================================
 */

session_start();        // Uruchomienie sesji – potrzebne do sprawdzania logowania
include("../cfg.php");  // Dołączenie konfiguracji połączenia z bazą danych


// =====================================================================
//  Sprawdzanie, czy użytkownik jest zalogowany.
//  Jeśli zmienna sesyjna 'logged_in' NIE istnieje → przekierowanie
//  do strony logowania i zakończenie działania skryptu.
// =====================================================================
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}


// Wyświetlenie nagłówka panelu admina i linku wylogowania
echo "<h1>Panel administratora</h1>";
echo "<a href='logout.php'>Wyloguj</a><br><br>";
echo "<a href='categories.php'>Zarządzaj kategoriami (LAB 10)</a><br><br>";



// =====================================================================
//  Pobranie wszystkich rekordów z tabeli page_list.
//  Tabela zawiera:
//      - id           → numer strony
//      - page_title   → tytuł strony
//      - status       → status (1 = aktywna, 0 = ukryta)
// =====================================================================
$result = mysqli_query($link, "SELECT * FROM page_list");


// =====================================================================
//  Generowanie tabeli HTML z listą podstron
// =====================================================================

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Tytuł</th><th>Status</th><th>Akcje</th></tr>";

// Wyświetlenie każdej podstrony w osobnym wierszu
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";

    // Kolumny tabeli
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['page_title'] . "</td>";
    echo "<td>" . $row['status'] . "</td>";

    // Akcje administratora:
    // - Edytuj (przejście do edycji treści)
    // - Usuń (operacja usunięcia rekordu z potwierdzeniem JS)
    echo "<td>
            <a href='edit.php?id=" . $row['id'] . "'>Edytuj</a> | 
            <a href='admin.php?delete=" . $row['id'] . "' onclick='return confirm(\"Usunąć?\");'>Usuń</a>
          </td>";

    echo "</tr>";
}

echo "</table>";


// =====================================================================
//  Usuwanie rekordów z bazy danych
//
//  Działanie:
//      1) Sprawdzenie, czy w adresie URL istnieje parametr delete
//      2) Jeśli tak — pobranie ID strony do usunięcia
//      3) Wykonanie zapytania SQL usuwającego stronę
//      4) Przekierowanie z powrotem do admin.php
//
//  Uwagi:
//      - Brak potwierdzenia serwerowego (jedynie JS)
//      - W realnych projektach należałoby stosować dodatkowe
//        zabezpieczenia oraz zapobiegać SQL Injection.
// =====================================================================
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];   // ID strony do usunięcia

    // Usunięcie rekordu z bazy danych
    mysqli_query($link, "DELETE FROM page_list WHERE id='$id'");

    // Przeładowanie panelu admina po wykonaniu operacji
    header("Location: admin.php");
}
?>
