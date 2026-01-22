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

session_start();


session_destroy();


header("Location: login.php");
