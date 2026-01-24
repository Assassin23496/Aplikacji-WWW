<?php
declare(strict_types=1);

class Auth
{
    private const SESSION_KEY = 'logged_in';

    // Project version: hardcoded credentials (can be moved to DB later)
    private const USERNAME = 'admin';
    private const PASSWORD = 'admin';

    public static function requireLogin(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (empty($_SESSION[self::SESSION_KEY])) {
            header("Location: login.php");
            exit();
        }
    }

    public static function attempt(string $username, string $password): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if ($username === self::USERNAME && $password === self::PASSWORD) {
            $_SESSION[self::SESSION_KEY] = true;
            return true;
        }
        return false;
    }

    public static function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
    }
}
