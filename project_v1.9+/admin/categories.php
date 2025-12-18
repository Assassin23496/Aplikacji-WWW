<?php
/*
 * ============================================================
 *  LAB 10 – Kategorie i podkategorie (rekurencja)
 *  Panel zarządzania kategoriami
 * ============================================================
 */

global $link;
session_start();
include("../cfg.php");

// zabezpieczenie dostępu
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

/*
 * ------------------------------------------------------------
 * Funkcja rekurencyjna – wyświetlanie drzewa kategorii
 * ------------------------------------------------------------
 */
function PokazKategorie($matka = 0)
{
    global $link;

    $sql = "SELECT * FROM categories WHERE matka = $matka";
    $result = mysqli_query($link, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<ul>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>";
            echo htmlspecialchars($row['nazwa']);
            echo " <a href='?delete=".$row['id']."' onclick=\"return confirm('Usunąć kategorię?')\">[Usuń]</a>";

            // REKURENCJA – pokazanie dzieci
            PokazKategorie($row['id']);

            echo "</li>";
        }

        echo "</ul>";
    }
}

/*
 * ------------------------------------------------------------
 * Dodawanie nowej kategorii
 * ------------------------------------------------------------
 */
if (isset($_POST['add'])) {
    $nazwa = mysqli_real_escape_string($link, $_POST['nazwa']);
    $matka = intval($_POST['matka']);

    mysqli_query(
        $link,
        "INSERT INTO categories (nazwa, matka) VALUES ('$nazwa', $matka)"
    );

    header("Location: categories.php");
    exit();
}

/*
 * ------------------------------------------------------------
 * Usuwanie kategorii + jej podkategorii
 * ------------------------------------------------------------
 */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // usunięcie podkategorii
    mysqli_query($link, "DELETE FROM categories WHERE matka = $id");

    // usunięcie kategorii
    mysqli_query($link, "DELETE FROM categories WHERE id = $id");

    header("Location: categories.php");
    exit();
}
?>

<h1>Zarządzanie kategoriami </h1>

<form method="post">
    <label>Nazwa kategorii:</label><br>
    <input type="text" name="nazwa" required><br><br>

    <label>Kategoria nadrzędna:</label><br>
    <select name="matka">
        <option value="0">— kategoria główna —</option>

        <?php
        $res = mysqli_query($link, "SELECT * FROM categories WHERE matka = 0");
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<option value='".$row['id']."'>".$row['nazwa']."</option>";
        }
        ?>
    </select><br><br>

    <button type="submit" name="add">Dodaj kategorię</button>
</form>

<hr>

<h2>Drzewo kategorii</h2>

<?php
// start od kategorii głównych
PokazKategorie();
?>

<br>
<a href="admin.php">⬅ Powrót do panelu administratora</a>
