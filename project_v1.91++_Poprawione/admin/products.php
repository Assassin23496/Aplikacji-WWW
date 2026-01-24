<?php
declare(strict_types=1);



require_once __DIR__ . "/../cfg.php";

Auth::requireLogin();

$productRepo = new ProductRepository($db);
$catRepo = new CategoryRepository($db);

function handleUploadedImage(string $fieldName, string $uploadDirRel): ?string
{
    if (empty($_FILES[$fieldName]) || ($_FILES[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($_FILES[$fieldName]['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException("Błąd uploadu pliku.");
    }

    $tmpPath = (string)$_FILES[$fieldName]['tmp_name'];
    $orig = (string)$_FILES[$fieldName]['name'];

    $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($ext, $allowed, true)) {
        throw new RuntimeException("Dozwolone formaty: JPG/PNG/WebP.");
    }

    if (($_FILES[$fieldName]['size'] ?? 0) > 2 * 1024 * 1024) {
        throw new RuntimeException("Plik za duży (max 2MB).");
    }

    $uploadAbs = dirname(__DIR__) . '/' . trim($uploadDirRel, '/');
    if (!is_dir($uploadAbs)) {
        if (!mkdir($uploadAbs, 0775, true) && !is_dir($uploadAbs)) {
            throw new RuntimeException("Brak katalogu upload: {$uploadDirRel}");
        }
    }

    $newName = "p_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
    $destAbs = $uploadAbs . "/" . $newName;

    if (!move_uploaded_file($tmpPath, $destAbs)) {
        throw new RuntimeException("Nie udało się zapisać pliku.");
    }

    return trim($uploadDirRel, '/') . "/" . $newName;
}

$error = null;

if (isset($_POST['add'])) {
    try {
        $imageUrl = trim((string)($_POST['image'] ?? ''));

        $uploaded = handleUploadedImage('image_file', 'images/products_upload');
        $imageToSave = $uploaded ?: $imageUrl;

        $productRepo->create([
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price_netto' => $_POST['price'] ?? 0,
            'vat' => $_POST['vat'] ?? 0,
            'quantity' => $_POST['qty'] ?? 0,
            'status' => $_POST['status'] ?? 0,
            'category_id' => $_POST['category'] ?? 0,
            'image' => $imageToSave,
        ]);

        header("Location: products.php");
        exit();
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $productRepo->delete($id);
    header("Location: products.php");
    exit();
}

$products = $db->fetchAll(
    "SELECT p.id, p.title, p.price_netto, p.quantity, p.status, c.nazwa AS kategoria
     FROM products p
     LEFT JOIN categories c ON p.category_id = c.id
     ORDER BY p.id DESC"
);
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Produkty</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="topbar">
    <a class="btn" href="admin.php">← Panel</a>
    <a class="btn" href="categories.php">Kategorie</a>
    <a class="btn danger" href="logout.php">Wyloguj</a>
</div>

<div class="container">

    <div class="card">
        <div class="card-h">
            <h1>Produkty</h1>
            <span class="pill">dodawanie + lista</span>
        </div>

        <div class="card-b">

            <?php if ($error): ?>
                <div class="notice danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <h2>Dodaj produkt</h2>

            <form method="post" enctype="multipart/form-data">
                <label>Tytuł</label>
                <input type="text" name="title" required>

                <label>Opis</label>
                <textarea name="description" rows="5"></textarea>

                <div class="form-row">
                    <div>
                        <label>Cena netto</label>
                        <input type="number" step="0.01" name="price" required>
                    </div>
                    <div>
                        <label>VAT (%)</label>
                        <input type="number" name="vat" value="23" required>
                    </div>
                    <div>
                        <label>Ilość (stan)</label>
                        <input type="number" name="qty" value="1" min="0" required>
                    </div>
                </div>

                <label>Zdjęcie (URL)</label>
                <input type="text" name="image" placeholder="https://example.com/photo.jpg">

                <label>Zdjęcie z komputera</label>
                <input type="file" name="image_file" id="image_file" accept="image/*" style="display:none;">
                <label for="image_file" class="btn">Wybierz zdjęcie z komputera</label>
                <div class="small">Jeśli wybierzesz plik — zostanie użyty zamiast URL (max 2MB, JPG/PNG/WebP).</div>

                <div class="form-row" style="margin-top:12px;">
                    <div>
                        <label>Kategoria</label>
                        <select name="category">
                            <option value="0">— brak / inne —</option>
                            <?= $catRepo->renderOptions(0, 0, 0) ?>
                        </select>
                    </div>
                    <div>
                        <label>Status</label>
                        <select name="status">
                            <option value="1">Aktywny</option>
                            <option value="0">Nieaktywny</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top:12px;">
                    <button type="submit" class="btn primary" name="add">Dodaj produkt</button>
                </div>
            </form>

            <hr>

            <h2>Lista produktów</h2>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tytuł</th>
                        <th>Kategoria</th>
                        <th>Cena</th>
                        <th>Stan</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?= (int)$p['id'] ?></td>
                            <td><?= htmlspecialchars((string)$p['title']) ?></td>
                            <td><?= htmlspecialchars((string)($p['kategoria'] ?? '')) ?></td>
                            <td><?= number_format((float)$p['price_netto'], 2) ?> zł</td>
                            <td><?= (int)$p['quantity'] ?></td>
                            <td><?= ((int)$p['status'] === 1) ? 'Aktywny' : 'Nieaktywny' ?></td>
                            <td>
                                <a class="btn danger" href="?delete=<?= (int)$p['id'] ?>"
                                   onclick="return confirm('Usunąć produkt?')">Usuń</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

</body>
</html>
