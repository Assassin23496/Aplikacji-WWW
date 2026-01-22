
<head>
    <link rel="stylesheet" href="admin.css">
    <title></title>
</head>
<?php
/*
 * =====================================================================
 *  Plik: edit.php
 *  Opis:
 *      Umożliwia administratorowi edycję wybranej podstrony zapisanej
 *      w tabeli "page_list". Formularz pozwala zmienić tytuł oraz treść.
 *
 *  Działanie pliku:
 *      - pobiera ID edytowanej strony z parametru GET
 *      - pobiera dane tej strony z bazy danych
 *      - wyświetla formularz z aktualnymi wartościami
 *      - po kliknięciu „Zapisz” — aktualizuje rekord w bazie
 *
 *  Wymagania LAB 9:
 *      - pełne komentarze do logiki PHP
 *      - opis działania modułu CMS
 * =====================================================================
 */

session_start();        // Uruchomienie sesji (wymagana, bo panel admina jest chroniony)
include("../cfg.php");  // Połączenie z bazą danych



$id = $_GET['id'];


$result = mysqli_query($link, "SELECT * FROM page_list WHERE id='$id'");
$row = mysqli_fetch_assoc($result);



if (isset($_POST['save'])) {

    $title = $_POST['title'];
    $content = $_POST['content'];

    mysqli_query($link,
            "UPDATE page_list SET page_title='$title', page_content='$content' WHERE id='$id'"
    );

    header("Location: admin.php");
    exit();
}
?>



<h2>Edycja strony ID: <?php echo $id; ?></h2>

<form method="post">

    Tytuł: <br>
    <input type="text" name="title" value="<?php echo $row['page_title']; ?>"><br><br>

    Treść: <br>
    <textarea name="content" rows="15" cols="80"><?php echo $row['page_content']; ?></textarea><br><br>

    <button type="submit" name="save">Zapisz</button>
</form>

<a href="admin.php">Powrót</a>
