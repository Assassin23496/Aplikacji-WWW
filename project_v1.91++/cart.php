<?php
session_start();
include("cfg.php"); //
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if (isset($_POST['ajax_add']) && isset($_POST['add_id'])) {
    $id = (int)$_POST['add_id'];
    if ($id > 0) {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    }
    echo "OK";
    exit();
}


if (isset($_POST['add_id']) && !isset($_POST['ajax_add'])) {
    $id = (int)$_POST['add_id'];
    if ($id > 0) {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    }
    header("Location: cart.php");
    exit();
}


if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}


$success = false;
if (isset($_POST['buy'])) {
    $_SESSION['cart'] = [];
    $success = true;
}


function getProductById($link, $id) {
    $id = (int)$id;
    $res = mysqli_query($link, "SELECT * FROM products WHERE id=$id LIMIT 1");
    return $res ? mysqli_fetch_assoc($res) : null;
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Koszyk</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="admin/admin.css">
    <link rel="stylesheet" href="CSS/shop.css">

</head>
<body>

<div class="container">
    <h1>Koszyk</h1>

    <a href="shop.php">← Powrót do sklepu</a><br><br>

    <?php if ($success): ?>
        <h2 style="color:lime;">Zakup zakończony sukcesem ✔</h2>
    <?php endif; ?>

    <?php
    if (empty($_SESSION['cart'])) {
        echo "<p>Koszyk jest pusty.</p>";
    } else {

        echo "<ul>";
        $sum = 0;

        foreach ($_SESSION['cart'] as $pid => $qty) {
            $p = getProductById($link, $pid);

            if (!$p) {
                unset($_SESSION['cart'][$pid]);
                continue;
            }

            $line = (float)$p['price_netto'] * (int)$qty;
            $sum += $line;

            $img = trim((string)$p['image']);

            if ($img !== "") {
                $src = $img;
                $imgHtml = "<img src='".htmlspecialchars($src)."' class='cart-img' alt='produkt'>";
            } else {
                $imgHtml = "<div class='cart-img placeholder'>—</div>";
            }

            echo "<li style='display:flex; align-items:center; gap:10px;'>
        $imgHtml
        <div>
            <strong>".htmlspecialchars($p['title'])."</strong><br>
            Ilość: ".(int)$qty."<br>
            Cena: ".number_format($line, 2, '.', '')." zł<br>
            <a href='?remove=".(int)$p['id']."'>Usuń</a>
        </div>
      </li>";

        }

        echo "</ul>";
        echo "<h3>Suma netto: ".number_format($sum, 2, '.', '')." zł</h3>";

        echo "
        <form method='post'>
            <button class='btn primary' name='buy'>Kup</button>
        </form>";
    }
    ?>
</div>

</body>
</html>
