<?php
/*
 * =====================================================================
 *  Plik: logout.php
 *  Opis:
 *      Moduł odpowiedzialny za wylogowanie użytkownika z panelu CMS.
 *
 *  Działanie:
 *      - usuwa wszystkie dane zapisane w sesji użytkownika
 *      - kończy sesję poprzez session_destroy()
 *      - przekierowuje użytkownika z powrotem do strony logowania
 *
 *  Wymagania LAB 9:
 *      - opis działania sesji
 *      - wyjaśnienie mechanizmu wylogowania
 * =====================================================================
 */

session_start();    // Rozpoczęcie sesji, aby można było ją zakończyć

// =====================================================================
//  Usunięcie wszystkich danych sesyjnych.
//  Funkcja session_destroy() usuwa sesję z serwera i czyści zmienne.
// =====================================================================
session_destroy();

// =====================================================================
//  Przekierowanie użytkownika z powrotem na stronę logowania.
//  Po wylogowaniu nie ma już dostępu do panelu admina.
// =====================================================================
header("Location: login.php");
