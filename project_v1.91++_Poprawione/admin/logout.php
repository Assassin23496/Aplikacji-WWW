<?php
declare(strict_types=1);

require_once __DIR__ . "/../cfg.php";

Auth::logout();
header("Location: login.php");
exit();
