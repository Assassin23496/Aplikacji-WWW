<?php
declare(strict_types=1);

require_once __DIR__ . "/cfg.php";

$catRepo = new CategoryRepository($db);
$productRepo = new ProductRepository($db);

// Filters
$q = trim((string)($_GET['q'] ?? ''));
$categoryId = ($_GET['cat'] ?? '') !== '' ? (int)$_GET['cat'] : null;
$priceMin = ($_GET['min'] ?? '') !== '' ? (float)$_GET['min'] : null;
$priceMax = ($_GET['max'] ?? '') !== '' ? (float)$_GET['max'] : null;
$sort = (string)($_GET['sort'] ?? 'newest');

$filters = [
    'q' => $q,
    'price_min' => $priceMin,
    'price_max' => $priceMax,
    'sort' => $sort,
];

if ($categoryId !== null) {
    // include descendants for filtering
    $ids = [$categoryId, ...$catRepo->getDescendantIds($categoryId)];
    $filters['category_ids'] = $ids;
}

$products = $productRepo->search($filters);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sklep – DOTA2fan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/shop.css">
</head>
<body>

<header class="shop-header">
    <div class="container">
        <h1>Sklep</h1>
        <nav>
            <a href="index.php">Strona</a>
            <a href="cart.php">Koszyk</a>
        </nav>
    </div>
</header>

<main class="container">

    <section class="filter-box">
        <form method="get" class="filter-grid">
            <div>
                <label>Wyszukaj</label>
                <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="np. Pudge, arcana...">
            </div>

            <div>
                <label>Kategoria</label>
                <select name="cat">
                    <option value="">— wszystkie —</option>
                    <?php
                    $selected = $categoryId ?? 0;
                    echo $catRepo->renderOptions($selected, 0, 0);
                    ?>
                </select>
            </div>

            <div>
                <label>Min cena</label>
                <input type="number" step="0.01" name="min" value="<?= $priceMin !== null ? htmlspecialchars((string)$priceMin) : '' ?>">
            </div>

            <div>
                <label>Max cena</label>
                <input type="number" step="0.01" name="max" value="<?= $priceMax !== null ? htmlspecialchars((string)$priceMax) : '' ?>">
            </div>

            <div>
                <label>Sortowanie</label>
                <select name="sort">
                    <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Najnowsze</option>
                    <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Cena rosnąco</option>
                    <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Cena malejąco</option>
                    <option value="title" <?= $sort === 'title' ? 'selected' : '' ?>>Nazwa A→Z</option>
                </select>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button class="btn-cart" type="submit">Filtruj</button>
                <a class="btn-cart" href="shop.php" style="text-decoration:none; display:inline-block;">Reset</a>
            </div>
        </form>
    </section>

    <div class="product-grid">
        <?php foreach ($products as $p): ?>
            <div class="product-card">

                <div class="product-img">
                    <?php if (!empty($p['image'])): ?>
                        <img src="<?= htmlspecialchars((string)$p['image']) ?>" alt="produkt">
                    <?php else: ?>
                        <div class="placeholder">Brak zdjęcia</div>
                    <?php endif; ?>
                </div>

                <h2><?= htmlspecialchars((string)$p['title']) ?></h2>

                <p class="category">
                    Kategoria: <?= htmlspecialchars((string)($p['kategoria'] ?? '')) ?>
                </p>

                <p class="price">
                    <?= number_format((float)$p['price_netto'], 2) ?> zł
                    <span>(VAT <?= (int)$p['vat'] ?>%)</span>
                </p>

                <div class="qty-wrap" style="margin:12px 0;">
                    <div class="qty-controls">
                        <span class="qty-label">Ilość:</span>
                        <button type="button" class="qty-step" data-step="-1" data-id="<?= (int)$p['id'] ?>">−</button>

                        <input class="qty-input" type="number" min="1" step="1" value="1"
                               data-id="<?= (int)$p['id'] ?>"
                               data-max="<?= ((int)$p['quantity'] > 0) ? (int)$p['quantity'] : 0 ?>">


                        <button type="button" class="qty-step" data-step="1" data-id="<?= (int)$p['id'] ?>">+</button>
                    </div>

                    <div class="qty-actions">
                        <button class="btn-cart add-cart" type="button" data-id="<?= (int)$p['id'] ?>">➕ Dodaj</button>
                        <a class="btn-cart" href="cart.php?add=<?= (int)$p['id'] ?>&qty=1" style="text-decoration:none;">Koszyk</a>
                    </div>
                </div>


                <p class="qty">
                    Stan: <?= (int)$p['quantity'] ?>
                </p>

            </div>
        <?php endforeach; ?>
    </div>

</main>

<script>
    function addToCart(productId, qty) {
        const formData = new FormData();
        formData.append('ajax_add', '1');
        formData.append('add_id', productId);
        formData.append('qty', qty);

        return fetch('cart.php', {
            method: 'POST',
            body: formData
        }).then(res => res.text());
    }

    // qty +/- buttons
    document.querySelectorAll('.qty-step').forEach(b => {
        b.addEventListener('click', function(){
            const id = this.dataset.id;
            const step = parseInt(this.dataset.step || '0', 10);
            const input = document.querySelector('.qty-input[data-id="'+id+'"]');
            if (!input) return;
            const min = parseInt(input.min || '1', 10);
            const max = parseInt(input.max || '999', 10);
            let val = parseInt(input.value || String(min), 10);
            if (Number.isNaN(val)) val = min;
            val = val + step;
            if (val < min) val = min;
            if (val > max) val = max;
            input.value = String(val);
        });
    });

    document.querySelectorAll('.add-cart').forEach(btn => {
        btn.addEventListener('click', function () {
            const productId = this.dataset.id;
            const qtyInput = document.querySelector('.qty-input[data-id="'+productId+'"]');
            const qty = qtyInput ? parseInt(qtyInput.value || '1', 10) : 1;

            const button = this;
            addToCart(productId, qty).then(() => {
                button.classList.add('added');
                button.textContent = '✔ Dodano';
                setTimeout(() => {
                    button.classList.remove('added');
                    button.textContent = '➕ Dodaj';
                }, 900);
            });
        });
    });

    
</script>

</body>
</html>
