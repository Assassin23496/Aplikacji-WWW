<?php
session_start();
include("../cfg.php");



if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($link, "DELETE FROM page_list WHERE id='$id'");
    header("Location: admin.php");
    exit();
}

$result = mysqli_query($link, "SELECT * FROM page_list");
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
                <div class="subbrand">CMS + sklep • styl Dota 2</div>
            </div>
        </div>

        <div class="nav">
            <a class="btn" href="../index.php">Strona główna</a>
            <a class="btn" href="categories.php">Kategorie</a>
            <a class="btn" href="products.php">Produkty</a>
            <a class="btn primary" href="logout.php">Wyloguj</a>
        </div>
    </div>
</div>

<div class="container">

    <div class="card">
        <div class="card-h">
            <h1>Panel administratora</h1>
            <span class="pill">Zalogowano</span>
        </div>

        <div class="card-b">
            <div class="grid">
                <div class="tile">
                    <strong>Skróty</strong>
                    <div class="muted">Zarządzaj treścią i sklepem</div>
                    <hr>
                    <a class="btn" href="categories.php">Kategorie</a>
                    <a class="btn" href="products.php">Produkty</a>
                </div>

                <div class="tile">
                    <strong>Info</strong>
                    <div class="muted">Strony CMS znajdują się w tabeli <b>page_list</b>.</div>
                    <div class="muted">Możesz je edytować lub usuwać.</div>
                </div>
            </div>

            <hr>

            <h2 style="margin:0 0 10px;">Lista podstron (CMS)</h2>

            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Tytuł</th>
                    <th>Status</th>
                    <th>Akcje</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['page_title']); ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <a class="btn" href="edit.php?id=<?php echo $row['id']; ?>">Edytuj</a>
                            <a class="btn" href="admin.php?delete=<?php echo $row['id']; ?>"
                               onclick="return confirm('Usunąć tę stronę?');">Usuń</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <div class="small" style="margin-top:12px;">
                Tip: status = 1 oznacza stronę aktywną w CMS.
            </div>

        </div>
    </div>

    <div class="small" style="margin-top:12px;">
        © <?php echo date('Y'); ?> • Panel admina
    </div>

</div>

</body>
</html>
