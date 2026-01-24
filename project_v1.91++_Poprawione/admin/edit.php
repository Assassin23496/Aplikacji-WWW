<?php
declare(strict_types=1);

require_once __DIR__ . "/../cfg.php";

Auth::requireLogin();

$pageRepo = new PageRepository($db);

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: admin.php");
    exit();
}

$row = $pageRepo->getById($id);
if (!$row) {
    header("Location: admin.php");
    exit();
}

$success = null;
$error = null;

if (isset($_POST['save'])) {
    try {
        $title = (string)($_POST['title'] ?? '');
        $content = (string)($_POST['content'] ?? '');
        $pageRepo->update($id, $title, $content);
        $success = "Zapisano zmiany.";
        $row = $pageRepo->getById($id) ?? $row;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Edycja podstrony</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="topbar">
    <a class="btn" href="admin.php">← Panel</a>
    <a class="btn danger" href="logout.php">Wyloguj</a>
</div>

<div class="container" style="max-width:900px;">
    <div class="card">
        <div class="card-h">
            <h1>Edycja podstrony</h1>
            <span class="pill">ID <?= $id ?></span>
        </div>
        <div class="card-b">
            <?php if ($success): ?>
                <div class="notice"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="notice danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post">
                <label>Tytuł</label>
                <input type="text" name="title" value="<?= htmlspecialchars((string)$row['page_title']) ?>" required>

                <label>Treść</label>
                <textarea name="content" rows="14"><?= htmlspecialchars((string)$row['page_content']) ?></textarea>

                <div style="margin-top:12px;">
                    <button class="btn primary" type="submit" name="save">Zapisz</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
