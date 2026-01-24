<?php
declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    $base = __DIR__ . '/classes/';
    $file = $base . $class . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});
