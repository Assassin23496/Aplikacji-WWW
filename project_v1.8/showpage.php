<?php
/*
 * ======================================================================
 *  Funkcja: PokazPodstrone($id)
 *  Opis:
 *      Funkcja odpowiada za pobranie treści wybranej podstrony z bazy
 *      danych na podstawie przekazanego identyfikatora (ID).
 *
 *  Działanie:
 *      1) Oczyszcza ID w celu zabezpieczenia przed SQL Injection
 *      2) Pobiera z bazy rekord o podanym ID i statusie aktywnym (status = 1)
 *      3) Zwraca treść strony (page_content)
 *      4) Jeśli strona nie istnieje → zwraca komunikat o błędzie
 *
 *  Plik spełnia wymagania LAB 9:
 *      - posiada komentarze blokowe opisujące funkcję
 *      - ma komentarze liniowe tłumaczące kluczowe operacje
 *      - używa LIMIT 1 (bezpieczeństwo i optymalizacja)
 * ======================================================================
 */

function PokazPodstrone($id)
{
    global $link;   // używamy połączenia z bazy z pliku cfg.php

    // --------------------------------------------------------------
    // Zabezpieczenie przed SQL Injection — oczyszczamy parametr ID.
    // Funkcja mysqli_real_escape_string() neutralizuje znaki
    // mogące posłużyć do wstrzyknięcia zapytania SQL.
    // --------------------------------------------------------------
    $id_clean = mysqli_real_escape_string($link, $id);

    // --------------------------------------------------------------
    // Zapytanie pobierające treść podstrony.
    // Używamy LIMIT 1, aby zwrócić maksymalnie jeden rekord
    // (wymaganie LAB9 — optymalizacja i bezpieczeństwo).
    // status = 1 oznacza, że strona jest aktywna.
    // --------------------------------------------------------------
    $query = "SELECT * FROM page_list WHERE id = '$id_clean' AND status = 1 LIMIT 1";

    // Wykonanie zapytania SQL
    $result = mysqli_query($link, $query);

    // Pobranie wyniku do tablicy asocjacyjnej
    $row = mysqli_fetch_array($result);

    // --------------------------------------------------------------
    // Jeżeli w bazie nie istnieje strona o podanym ID,
    // funkcja zwróci komunikat błędu zamiast pustego ekranu.
    // --------------------------------------------------------------
    if (empty($row['id'])) {
        return '<h2>Nie znaleziono strony!</h2>';
    } else {
        // Zwracamy treść podstrony zapisaną w bazie danych
        return $row['page_content'];
    }
}
?>
