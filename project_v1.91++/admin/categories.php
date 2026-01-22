<?php
session_start();
include("../cfg.php");

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

function PokazKategorie($matka = 0)
{
    global $link;

    $sql = "SELECT * FROM categories WHERE matka = $matka";
    $result = mysqli_query($link, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<ul>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>";
            echo "<span class='cat-name'>".htmlspecialchars($row['nazwa'])."</span>";

            echo "<div class='category-actions'>
                    <a class='btn danger' href='?delete=".$row['id']."' onclick=\"return confirm('Usunąć kategorię?')\">Usuń</a>
                  </div>";

            // rekurencja
            PokazKategorie($row['id']);

            echo "</li>";
        }

        echo "</ul>";
    }
}

if (isset($_POST['add'])) {
    $nazwa = mysqli_real_escape_string($link, $_POST['nazwa']);
    $matka = intval($_POST['matka']);

    mysqli_query($link, "INSERT INTO categories (nazwa, matka) VALUES ('$nazwa', $matka)");
    header("Location: categories.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    mysqli_query($link, "DELETE FROM categories WHERE matka = $id");
    mysqli_query($link, "DELETE FROM categories WHERE id = $id");

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
<body class="admin-page">

<div class="container">
    <div class="card">
        <div class="card-h">
            <h1>Kategorie</h1>
            <span class="pill">drzewo</span>
        </div>
        <div class="card-b">

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
                            <?php
                            $res = mysqli_query($link, "SELECT * FROM categories WHERE matka = 0");
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo "<option value='".$row['id']."'>".htmlspecialchars($row['nazwa'])."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div style="margin-top:12px;">
                    <button class="btn primary" type="submit" name="add">Dodaj kategorię</button>
                    <a class="btn" href="admin.php">Powrót</a>
                </div>
            </form>

            <hr>

            <h2>Drzewo kategorii</h2>

            <div class="category-tree">
                <?php PokazKategorie(); ?>
            </div>

        </div>
    </div>
</div>

</body>
</html>
