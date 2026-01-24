<?php
declare(strict_types=1);

require_once __DIR__ . '/autoload.php';

// Session is needed in most modules (auth/cart).
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Backward-compatible config variables.
$db_host = $db_host ?? "localhost";
$db_user = $db_user ?? "root";
$db_pass = $db_pass ?? "";
$db_name = $db_name ?? "moja_strona";

$db = new Database($db_host, $db_user, $db_pass, $db_name);
$link = $db->getConnection(); // legacy code compatibility
