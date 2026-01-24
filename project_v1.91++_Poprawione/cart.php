<?php
declare(strict_types=1);

require_once __DIR__ . "/cfg.php";

$cart = new Cart();
$productRepo = new ProductRepository($db);

// AJAX add (from shop)
if (isset($_POST['ajax_add']) && isset($_POST['add_id'])) {
    $id = (int)$_POST['add_id'];
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
    if ($id > 0) {
        $cart->add($id, max(1, $qty));
    }
    echo "OK";
    exit();
}

if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    $qty = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;
    if ($id > 0) {
        $cart->add($id, max(1, $qty));
    }
    header("Location: cart.php");
    exit();
}

if (isset($_POST['set_qty'])) {
    $id = (int)($_POST['id'] ?? 0);
    $qty = (int)($_POST['qty'] ?? 0);
    if ($id > 0) {
        $cart->setQty($id, $qty);
    }
    header("Location: cart.php");
    exit();
}

if (isset($_GET['inc'])) {
    $id = (int)$_GET['inc'];
    if ($id > 0) $cart->add($id, 1);
    header("Location: cart.php");
    exit();
}

if (isset($_GET['dec'])) {
    $id = (int)$_GET['dec'];
    if ($id > 0) {
        $items = $cart->items();
        $current = (int)($items[$id] ?? 0);
        $cart->setQty($id, $current - 1);
    }
    header("Location: cart.php");
    exit();
}

if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    if ($id > 0) $cart->remove($id);
    header("Location: cart.php");
    exit();
}

if (isset($_POST['clear'])) {
    $cart->clear();
    header("Location: cart.php");
    exit();
}
if (isset($_POST['buy'])) {

    $cart->clear();
    header("Location: cart.php?bought=1");
    exit();
}

?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Koszyk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/shop.css">
    <style>
        .cart-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 12px; }
        .cart-item { display:flex; gap:12px; align-items:center; background:#141414; border:1px solid #232323; border-radius:14px; padding:12px; }
        .cart-img { width:86px; height:86px; object-fit:cover; border-radius:12px; border:1px solid #2a2a2a; }
        .cart-img.placeholder { display:flex; align-items:center; justify-content:center; background:#1b1b1b; color:#777; }
        .cart-actions { margin-left:auto; display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
        .qty-form { display:flex; gap:6px; align-items:center; }
        .qty-form input { width:84px; }
        .mini-btn { padding:8px 10px; border-radius:10px; border:1px solid #2a2a2a; background:#1a1a1a; color:#fff; cursor:pointer; }
        .mini-btn:hover { filter:brightness(1.15); }
        .totals { margin-top:16px; display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:center; }
    </style>
</head>
<body>

<header class="shop-header">
    <div class="container">
        <h1>Koszyk</h1>
        <nav>
            <a href="shop.php">Sklep</a>
            <a href="index.php">Strona</a>
        </nav>
    </div>
</header>

<main class="container">
    <?php if (isset($_GET['bought'])): ?>
        <p style="margin:12px 0; font-weight:800; color:#22c55e;">
            ✔ Zakup zakończony. Koszyk wyczyszczony.
        </p>
    <?php endif; ?>


    <?php
    $items = $cart->items();
    if (!$items) {
        echo "<p>Koszyk jest pusty.</p>";
    } else {
        $sum = 0.0;
        echo "<ul class='cart-list'>";

        foreach ($items as $pid => $qty) {
            $p = $productRepo->getById((int)$pid);

            if (!$p) {
                $cart->remove((int)$pid);
                continue;
            }

            $price = (float)$p['price_netto'];
            $line = $price * (int)$qty;
            $sum += $line;

            $img = trim((string)($p['image'] ?? ''));
            if ($img !== '') {
                $imgHtml = "<img src='".htmlspecialchars($img)."' class='cart-img' alt='produkt'>";
            } else {
                $imgHtml = "<div class='cart-img placeholder'>—</div>";
            }

            echo "<li class='cart-item'>
                    {$imgHtml}
                    <div>
                        <strong>".htmlspecialchars((string)$p['title'])."</strong><br>
                        <span class='small'>Cena: ".number_format($price, 2, '.', '')." zł</span><br>
                        <span class='small'>Suma: ".number_format($line, 2, '.', '')." zł</span>
                    </div>

                    <div class='cart-actions'>
                        <a class='mini-btn' href='?dec=".(int)$pid."' title='-1'>−</a>
                        <a class='mini-btn' href='?inc=".(int)$pid."' title='+1'>+</a>

                        <form class='qty-form' method='post'>
                            <input type='hidden' name='id' value='".(int)$pid."'>
                            <input type='number' min='0' name='qty' value='".(int)$qty."'>
                            <button class='mini-btn' type='submit' name='set_qty'>Ustaw</button>
                        </form>

                        <a class='mini-btn' href='?remove=".(int)$pid."' onclick='return confirm(\'Usunąć pozycję?\')'>Usuń</a>
                    </div>
                  </li>";
        }

        echo "</ul>";

        echo "<div class='totals'>
        <h3>Suma netto: ".number_format($sum, 2, '.', '')." zł</h3>
        <form method='post' style='display:flex; gap:8px; align-items:center; flex-wrap:wrap;'>
            <button class='btn-cart' type='submit' name='buy' onclick='return confirm(\'Potwierdzić zakup?\')'>Kup</button>
            <button class='mini-btn' type='submit' name='clear' onclick='return confirm(\'Wyczyścić koszyk?\')'>Wyczyść</button>
        </form>
      </div>";

    }
    ?>
</main>

</body>
</html>