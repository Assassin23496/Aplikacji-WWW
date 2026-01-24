<?php
declare(strict_types=1);

require_once __DIR__ . "/../cfg.php";

Auth::requireLogin();

$pageRepo = new PageRepository($db);

$notice = null;
$error = null;

if (isset($_POST['add_page'])) {
    try {
        $title = (string)($_POST['title'] ?? '');
        $content = (string)($_POST['content'] ?? '');
        $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
        if (trim($title) === '') {
            throw new RuntimeException('Tytuł jest wymagany.');
        }
        $pageRepo->create($title, $content, $status);
        $notice = 'Dodano podstronę.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pageRepo->delete($id);
    header("Location: admin.php");
    exit();
}

$pages = $pageRepo->listAll();
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel administratora</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="topbar">
    <div class="container">
        <div class="brand">
            <div class="badge">D2</div>
            <div>
                <div>Panel administratora</div>
                <div class="small">CMS + sklep</div>
            </div>
        </div>

        <div class="actions">
            <a class="btn" href="categories.php">Kategorie</a>
            <a class="btn" href="products.php">Produkty</a>
            <a class="btn danger" href="logout.php">Wyloguj</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="grid-2">

        <div class="card">
            <div class="card-h">
                <h1>Podstrony</h1>
                <span class="pill">page_list</span>
            </div>
            <div class="card-b">

                <?php if ($notice): ?>
                    <div class="notice"><?= htmlspecialchars($notice) ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="notice danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <h2>Dodaj podstronę</h2>
                <form method="post" style="margin-top:10px;">
                    <label>Tytuł</label>
                    <input type="text" name="title" required>

                    <label>Treść</label>
                    <textarea name="content" rows="6"></textarea>

                    <label>Status</label>
                    <select name="status">
                        <option value="1">Aktywna</option>
                        <option value="0">Nieaktywna</option>
                    </select>

                    <div style="margin-top:10px;">
                        <button class="btn primary" type="submit" name="add_page">Dodaj</button>
                    </div>
                </form>

                <hr style="border:0; border-top:1px solid rgba(255,255,255,.12); margin:16px 0;">

                <div class="table-wrap">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tytuł</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pages as $p): ?>
                            <tr>
                                <td><?= (int)$p['id'] ?></td>
                                <td><?= htmlspecialchars((string)$p['page_title']) ?></td>
                                <td><?= ((int)$p['status'] === 1) ? 'Aktywna' : 'Nieaktywna' ?></td>
                                <td style="white-space:nowrap;">
                                    <a class="btn" href="edit.php?id=<?= (int)$p['id'] ?>">Edytuj</a>
                                    <a class="btn danger" href="?delete=<?= (int)$p['id'] ?>"
                                       onclick="return confirm('Usunąć podstronę?')">Usuń</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="small" style="margin-top:10px;">
                    Uwaga: usuwanie jest trwałe (DELETE).
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-h">
                <h1>Skróty</h1>
                <span class="pill">nawigacja</span>
            </div>
            <div class="card-b">
                <a class="btn" href="../index.php" target="_blank">Otwórz stronę</a>
                <a class="btn" href="../shop.php" target="_blank">Otwórz sklep</a>
                <a class="btn" href="../cart.php" target="_blank">Otwórz koszyk</a>
            </div>
        </div>

    </div>
</div>

</body>
</html>
