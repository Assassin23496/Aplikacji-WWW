<?php
include("cfg.php");

$res = mysqli_query($link, "
    SELECT p.*, c.nazwa AS kategoria
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 1
    ORDER BY p.id DESC
");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sklep – DOTA2fan</title>

    <link rel="stylesheet" href="CSS/shop.css">

</head>
<body>

<header class="shop-header">
    <div class="logo">DOTA<span>2</span>fan</div>
    <nav>
        <a href="index.php">Strona główna</a>
        <a href="shop.php" class="active">Sklep</a>
        <a href="cart.php">Koszyk</a>

    </nav>
</header>

<main class="shop-container">
    <h1>Sklep Dota 2</h1>

    <div class="product-grid">
        <?php while ($p = mysqli_fetch_assoc($res)): ?>

            <div class="product-card">
                <div class="product-id">ID: <?= $p['id'] ?></div>

                <?php
                $img = trim((string)$p['image']);
                $src = "";
                if ($img !== "") {
                    $isExternal = (strpos($img, "http://") === 0 || strpos($img, "https://") === 0);
                    $src = $isExternal ? $img : $img;
                }
                ?>

                <div class="product-thumb">
                    <?php if ($src !== ""): ?>
                        <img src="<?php echo htmlspecialchars($src); ?>" alt="produkt">
                    <?php else: ?>
                        <div class="thumb-placeholder">brak zdjęcia</div>
                    <?php endif; ?>
                </div>


                <h2><?= htmlspecialchars($p['title']) ?></h2>

                <p class="category">
                    Kategoria: <?= htmlspecialchars($p['kategoria']) ?>
                </p>

                <p class="price">
                    <?= number_format($p['price_netto'], 2) ?> zł
                    <span>(VAT <?= $p['vat'] ?>%)</span>
                </p>
                <button class="btn-cart add-cart" data-id="<?php echo $p['id']; ?>">
                    ➕ Dodaj do koszyka
                </button>



                <p class="qty">
                    Ilość: <?= $p['quantity'] ?>
                </p>

                <button class="btn-cart buy-now" data-id="<?php echo $p['id']; ?>">
                    Kup teraz
                </button>

            </div>

        <?php endwhile; ?>
    </div>
</main>

<script>
    function addToCart(productId) {
        return fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'ajax_add=1&add_id=' + encodeURIComponent(productId)
        }).then(res => res.text());
    }

    // Dodaj do koszyka (bez przekierowania)
    document.querySelectorAll('.add-cart').forEach(btn => {
        btn.addEventListener('click', function () {
            const productId = this.dataset.id;
            const button = this;

            addToCart(productId).then(() => {
                button.classList.add('added');
                button.textContent = '✔ Dodano';
                setTimeout(() => {
                    button.classList.remove('added');
                    button.textContent = '➕ Dodaj do koszyka';
                }, 900);
            });
        });
    });

    // Kup teraz (dodaje + przekierowuje do koszyka)
    document.querySelectorAll('.buy-now').forEach(btn => {
        btn.addEventListener('click', function () {
            const productId = this.dataset.id;

            addToCart(productId).then(() => {
                window.location.href = 'cart.php';
            });
        });
    });
</script>


<footer class="shop-footer">
    © <?= date('Y') ?> DOTA2fan – sklep inspirowany Dota 2
</footer>

</body>
</html>
