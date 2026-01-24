<?php
declare(strict_types=1);

class Cart
{
    private const SESSION_KEY = 'cart';

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION[self::SESSION_KEY]) || !is_array($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }
    }

    /** @return array<int,int> */
    public function items(): array
    {
        /** @var array<int,int> */
        return $_SESSION[self::SESSION_KEY];
    }

    public function add(int $productId, int $qty = 1): void
    {
        $productId = (int)$productId;
        $qty = max(1, (int)$qty);
        $_SESSION[self::SESSION_KEY][$productId] = (int)($_SESSION[self::SESSION_KEY][$productId] ?? 0) + $qty;
    }

    public function setQty(int $productId, int $qty): void
    {
        $productId = (int)$productId;
        $qty = (int)$qty;
        if ($qty <= 0) {
            $this->remove($productId);
            return;
        }
        $_SESSION[self::SESSION_KEY][$productId] = $qty;
    }

    public function remove(int $productId): void
    {
        unset($_SESSION[self::SESSION_KEY][(int)$productId]);
    }

    public function clear(): void
    {
        $_SESSION[self::SESSION_KEY] = [];
    }
}
