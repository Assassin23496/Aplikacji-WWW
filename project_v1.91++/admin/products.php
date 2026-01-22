<?php
/*
 * LAB 11 – Zarządzanie produktami
 * Autor: Raman Voitsiuk
 */

global $link;
session_start();
include("../cfg.php");

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}


if (isset($_POST['add'])) {
    $title  = $_POST['title'];
    $desc   = $_POST['description'];
    $price  = $_POST['price'];
    $vat    = $_POST['vat'];
    $qty    = $_POST['quantity'];
    $cat    = $_POST['category'];
    $status = $_POST['status'];

    $image = isset($_POST['image']) ? trim($_POST['image']) : "";

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {

        $tmpPath = $_FILES['image_file']['tmp_name'];
        $size    = (int)$_FILES['image_file']['size'];

        // limit 2MB
        if ($size > 2 * 1024 * 1024) {
            die("Plik jest za duży (max 2MB).");
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $tmpPath);
        finfo_close($finfo);

        $allowed = [
                "image/jpeg" => "jpg",
                "image/png"  => "png",
                "image/webp" => "webp"
        ];

        if (!isset($allowed[$mime])) {
            die("Niedozwolony typ pliku. Dozwolone: JPG/PNG/WebP.");
        }

        $ext = $allowed[$mime];
        $newName = "p_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;

        $destDir = __DIR__ . "/../images/products_upload/";
        $destPath = $destDir . $newName;

        if (!is_dir($destDir)) {
            die("Brak katalogu upload: images/products_upload/");
        }

        if (!move_uploaded_file($tmpPath, $destPath)) {
            die("Nie udało się zapisać pliku.");
        }

        $image = "images/products_upload/" . $newName;
    }

    mysqli_query($link, "
       INSERT INTO products 
       (title, description, created_at, price_netto, vat, quantity, status, category_id, image)
       VALUES
       ('$title','$desc',CURDATE(),'$price','$vat','$qty','$status','$cat','$image')
    ");

    header("Location: products.php");
    exit();
}


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($link, "DELETE FROM products WHERE id=$id");
    header("Location: products.php");
    exit();
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Produkty</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-page">

<div class="container">
    <div class="card">
        <div class="card-h">
            <h1>Produkty</h1>
            <span class="pill">panel</span>
        </div>
        <div class="card-b">

            <h2>Dodaj produkt</h2>

            <form method="post" enctype="multipart/form-data">

                <label>Nazwa produktu</label>
                <input name="title" placeholder="Nazwa produktu" required>

                <label>Opis produktu</label>
                <textarea name="description" placeholder="Opis produktu" required></textarea>

                <div class="form-row">
                    <div>
                        <label>Cena netto</label>
                        <input name="price" placeholder="Cena netto" required>
                    </div>
                    <div>
                        <label>VAT %</label>
                        <input name="vat" placeholder="VAT %" required>
                    </div>
                    <div>
                        <label>Ilość</label>
                        <input name="quantity" placeholder="Ilość" required>
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
                            <?php
                            $cats = mysqli_query($link, "SELECT * FROM categories");
                            while ($c = mysqli_fetch_assoc($cats)) {
                                echo "<option value='{$c['id']}'>".htmlspecialchars($c['nazwa'])."</option>";
                            }
                            ?>
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
                    <a class="btn" href="admin.php">Powrót</a>
                </div>
            </form>

            <hr>

            <h2>Lista produktów</h2>

            <?php
            $res = mysqli_query($link, "
                SELECT p.*, c.nazwa 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.id DESC
            ");

            echo "<div class='product-grid'>";

            while ($p = mysqli_fetch_assoc($res)) {

                echo "<div class='product-card'>";

                echo "<div class='pill'>ID: {$p['id']}</div>";
                echo "<div class='title'>".htmlspecialchars($p['title'])."</div>";

                echo "<div class='meta'>
                        <b>Kategoria:</b> ".htmlspecialchars($p['nazwa'] ?? '')."<br>
                        <b>Cena:</b> {$p['price_netto']} zł (VAT {$p['vat']}%)<br>
                        <b>Ilość:</b> {$p['quantity']}
                      </div>";

                $img = trim((string)$p['image']);
                if ($img !== "") {
                    $isExternal = (strpos($img, "http://") === 0 || strpos($img, "https://") === 0);
                    $src = $isExternal ? $img : "../" . $img;

                    echo "<img class='product-img' src='".htmlspecialchars($src)."' alt='produkt'>";
                } else {
                    echo "<div class='small' style='margin-top:10px;'>Brak zdjęcia</div>";
                }

                echo "<div class='product-actions'>
                        <a class='btn danger' href='?delete={$p['id']}' onclick=\"return confirm('Usunąć produkt?')\">Usuń</a>
                      </div>";

                echo "</div>";
            }

            echo "</div>";
            ?>
        </div>
    </div>
</div>

</body>
</html>
