<?php
/*
 * LAB 11 – Zarządzanie produktami
 * Autor: Raman Vaitsiuk
 */

global $link;
session_start();
include("../cfg.php");

// zabezpieczenie dostępu
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

/* ---------------- DODAWANIE PRODUKTU ---------------- */
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $vat = $_POST['vat'];
    $qty = $_POST['quantity'];
    $cat = $_POST['category'];
    $status = $_POST['status'];
    $image = $_POST['image'];


    mysqli_query($link, "
       INSERT INTO products 
    (title, description, created_at, price_netto, vat, quantity, status, category_id, image)
        VALUES
('$title','$desc',CURDATE(),'$price','$vat','$qty','$status','$cat','$image')

    ");

    header("Location: products.php");
    exit();
}

/* ---------------- USUWANIE PRODUKTU ---------------- */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($link, "DELETE FROM products WHERE id=$id");
    header("Location: products.php");
    exit();
}
?>

<h1>Produkty</h1>

<h2>Dodaj produkt</h2>

<form method="post">
    <input name="title" placeholder="Nazwa produktu" required><br><br>

    <textarea name="description" placeholder="Opis produktu" required></textarea><br><br>

    <input name="price" placeholder="Cena netto" required><br><br>

    <input name="vat" placeholder="VAT %" required><br><br>

    <input name="quantity" placeholder="Ilość" required><br><br>

    <label>Zdjęcie (URL):</label><br>
    <input type="text" name="image" placeholder="https://example.com/photo.jpg"><br><br>

    <label>Kategoria:</label><br>
    <select name="category">
        <?php
        $cats = mysqli_query($link, "SELECT * FROM categories");
        while ($c = mysqli_fetch_assoc($cats)) {
            echo "<option value='{$c['id']}'>{$c['nazwa']}</option>";
        }
        ?>
    </select><br><br>

    <label>Status:</label><br>
    <select name="status">
        <option value="1">Aktywny</option>
        <option value="0">Nieaktywny</option>
    </select><br><br>

    <button type="submit" name="add">Dodaj produkt</button>
</form>

<hr>

<h2>Lista produktów</h2>

<?php
$res = mysqli_query($link, "
    SELECT p.*, c.nazwa 
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
");

echo "<div style='
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    margin-top:20px;
'>";

while ($p = mysqli_fetch_assoc($res)) {

    echo "
    <div style='
        border:1px solid #ccc;
        padding:10px;
        width:300px;
        box-sizing:border-box;
    '>

        <strong>ID:</strong> {$p['id']}<br>

        <strong>{$p['title']}</strong><br>

        <em>Kategoria:</em> {$p['nazwa']}<br>
        <em>Cena:</em> {$p['price_netto']} zł (VAT {$p['vat']}%)<br>
        <em>Ilość:</em> {$p['quantity']}<br><br>
    ";

    // jeśli podano URL zdjęcia
    if (!empty($p['image'])) {
        echo "
        <img src='{$p['image']}'
             style='
                max-width:200px;
                border:2px solid #000;
                padding:4px;
             '><br><br>";
    }

    echo "
        <a href='?delete={$p['id']}'>[Usuń]</a>
    </div>";
}

echo "</div>";

?>

<br>
<a href="admin.php">⬅ Powrót do panelu</a>

