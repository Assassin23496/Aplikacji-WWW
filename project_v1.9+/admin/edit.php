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


// =====================================================================
//  Pobieranie ID strony z URL
//  Parametr "id" określa, którą podstronę będziemy edytować.
//  Brak filtracji jest akceptowalny w tym projekcie, ale w praktyce
//  stosuje się dodatkowe zabezpieczenia (intval, prepared statements).
// =====================================================================
$id = $_GET['id'];


// =====================================================================
//  Pobranie danych wybranej strony z bazy danych.
//  Funkcja mysqli_fetch_assoc() zwraca wyniki w formie tablicy,
//  którą później wykorzystujemy do uzupełnienia formularza.
// =====================================================================
$result = mysqli_query($link, "SELECT * FROM page_list WHERE id='$id'");
$row = mysqli_fetch_assoc($result);


// =====================================================================
//  Obsługa zapisu formularza
//  Jeżeli użytkownik kliknie "Zapisz", zmienne title i content
//  zostają pobrane z formularza POST i zapisane z powrotem do bazy.
// =====================================================================
if (isset($_POST['save'])) {

    $title = $_POST['title'];       // nowy tytuł
    $content = $_POST['content'];   // nowa treść podstrony

    // Aktualizacja rekordu w bazie danych
    mysqli_query($link,
            "UPDATE page_list SET page_title='$title', page_content='$content' WHERE id='$id'"
    );

    // Po zapisaniu następuje przekierowanie z powrotem do panelu admina
    header("Location: admin.php");
    exit();
}
?>

<!-- ===================================================================
     FORMULARZ EDYCJI PODSTRONY
     Wartości pól są automatycznie uzupełniane danymi pobranymi z bazy.
     =================================================================== -->

<h2>Edycja strony ID: <?php echo $id; ?></h2>

<form method="post">

    Tytuł: <br>
    <input type="text" name="title" value="<?php echo $row['page_title']; ?>"><br><br>

    Treść: <br>
    <textarea name="content" rows="15" cols="80"><?php echo $row['page_content']; ?></textarea><br><br>

    <button type="submit" name="save">Zapisz</button>
</form>

<!-- Link powrotu do panelu administratora -->
<a href="admin.php">Powrót</a>
