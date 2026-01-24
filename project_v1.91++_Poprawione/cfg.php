<?php
/*
 * cfg.php (legacy)
 * - Trzyma dane dostępu do bazy (XAMPP)
 * - Tworzy obiekt Database i zmienną $link (mysqli) dla zgodności wstecz
 *
 * Uwaga: baza danych już istnieje – tego pliku nie używamy do tworzenia tabel.
 */

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "moja_strona";

require_once __DIR__ . "/app/bootstrap.php";
