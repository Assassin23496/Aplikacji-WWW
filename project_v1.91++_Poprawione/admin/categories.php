<?php
declare(strict_types=1);

require_once __DIR__ . "/../cfg.php";

Auth::requireLogin();

$catRepo = new CategoryRepository($db);

$error = null;

if (isset($_POST['add'])) {
    try {
        $name = (string)($_POST['nazwa'] ?? '');
        $parent = (int)($_POST['matka'] ?? 0);
        $catRepo->create($name, $parent);
        header("Location: categories.php");
        exit();
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $catRepo->deleteRecursive($id);
    header("Location: categories.php");
    exit();
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Kategorie</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="topbar">
    <a class="btn" href="admin.php">← Panel</a>
    <a class="btn" href="products.php">Produkty</a>
    <a class="btn danger" href="logout.php">Wyloguj</a>
</div>

<div class="container">
    <div class="card">
        <div class="card-h">
            <h1>Kategorie</h1>
            <span class="pill">drzewo + podkategorie</span>
        </div>
        <div class="card-b">

            <?php if ($error): ?>
                <div class="notice danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <h2>Dodaj kategorię</h2>

            <form method="post">
                <div class="form-split">
                    <div>
                        <label>Nazwa kategorii</label>
                        <input type="text" name="nazwa" required>
                    </div>

                    <div>
                        <label>Kategoria nadrzędna</label>
                        <select name="matka">
                            <option value="0">— kategoria główna —</option>
                            <?= $catRepo->renderOptions(0, 0, 0) ?>
                        </select>
                        <div class="small">Możesz wybrać dowolną kategorię (również zagnieżdżoną) jako nadrzędną.</div>
                    </div>
                </div>

                <div style="margin-top:12px;">
                    <button type="submit" class="btn primary" name="add">Dodaj</button>
                </div>
            </form>

            <hr>

            <h2>Drzewo kategorii</h2>
            <div class="category-tree">
                <?= $catRepo->renderTree(0) ?>
            </div>

        </div>
    </div>
</div>

</body>
</html>
