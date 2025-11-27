<?php
session_start();

include "cfg.php";


function FormularzLogowania()
{
    return '
    <h2>Logowanie do CMS</h2>
    <form method="post">
        <label>E-mail:<br>
            <input type="text" name="login_email">
        </label><br><br>
        <label>Hasło:<br>
            <input type="password" name="login_pass">
        </label><br><br>
        <input type="submit" name="loguj" value="Zaloguj">
    </form>';
}

function AdminLogin()
{
    global $login, $pass;

    if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) return true;

    if (!isset($_POST['loguj'])) return false;

    if ($_POST['login_email'] === $login && $_POST['login_pass'] === $pass) {
        $_SESSION['logged'] = true;
        return true;
    }

    echo "<p style='color:red;'>Błędny login lub hasło!</p>";
    return false;
}

function ListaPodstron()
{
    global $link;

    $result = mysqli_query($link, "SELECT * FROM page_list ORDER BY id ASC");

    $html = "<h2>Lista podstron</h2>";
    $html .= '<a href="?admin=add">+ Dodaj nową podstronę</a><br><br>';
    $html .= "<table border='1' cellpadding='7'>
                <tr><th>ID</th><th>Tytuł</th><th>Status</th><th>Opcje</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        $status = $row['active'] ? 'Aktywna' : 'Nieaktywna';
        $html .= "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['page_title']}</td>
            <td>{$status}</td>
            <td>
                <a href='?admin=edit&id={$row['id']}'>Edytuj</a> |
                <a href='?admin=delete&id={$row['id']}' onclick='return confirm(\"Czy na pewno chcesz usunąć tę podstronę?\")'>Usuń</a>
            </td>
        </tr>";
    }

    $html .= "</table>";

    return $html;
}

function EdytujPodstrone()
{
    global $link;

    if (!isset($_GET['id'])) return "Brak ID";

    $id = (int)$_GET['id'];

    if (isset($_POST['save'])) {
        $title = mysqli_real_escape_string($link, $_POST['title']);
        $content = mysqli_real_escape_string($link, $_POST['content']);
        $active = isset($_POST['active']) ? 1 : 0;

        $query = "UPDATE page_list
                 SET page_title='$title', page_content='$content', active='$active'
                 WHERE id=$id LIMIT 1";

        if (mysqli_query($link, $query)) {
            echo "<p style='color:green;'>Zapisano zmiany!</p>";
        } else {
            echo "<p style='color:red;'>Błąd zapisu: " . mysqli_error($link) . "</p>";
        }
    }

    $q = mysqli_query($link, "SELECT * FROM page_list WHERE id=$id LIMIT 1");

    if (mysqli_num_rows($q) == 0) {
        return "<p style='color:red;'>Podstrona nie istnieje!</p>";
    }

    $d = mysqli_fetch_assoc($q);

    return '
    <h2>Edycja podstrony</h2>
    <form method="post">
        Tytuł:<br>
        <input type="text" name="title" value="'.htmlspecialchars($d['page_title']).'" style="width: 300px;"><br><br>

        Treść:<br>
        <textarea name="content" rows="15" cols="80">'.htmlspecialchars($d['page_content']).'</textarea><br><br>

        Aktywna:
        <input type="checkbox" name="active" '.($d['active'] ? "checked" : "").'><br><br>

        <input type="submit" name="save" value="Zapisz zmiany">
        <a href="admin.php" style="margin-left: 20px;">Powrót do listy</a>
    </form>';
}

function DodajNowaPodstrone()
{
    global $link;

    if (isset($_POST['add'])) {
        $title = mysqli_real_escape_string($link, $_POST['title']);
        $content = mysqli_real_escape_string($link, $_POST['content']);
        $active = isset($_POST['active']) ? 1 : 0;

        $query = "INSERT INTO page_list (page_title, page_content, active)
          VALUES ('$title', '$content', $active)";


        if (mysqli_query($link, $query)) {
            echo "<p style='color:green;'>Dodano nową podstronę!</p>";
        } else {
            echo "<p style='color:red;'>Błąd dodawania: " . mysqli_error($link) . "</p>";
        }
    }

    return '
    <h2>Dodaj nową podstronę</h2>
    <form method="post">
        Tytuł:<br>
        <input type="text" name="title" style="width: 300px;"><br><br>

        Treść:<br>
        <textarea name="content" rows="15" cols="80"></textarea><br><br>

        Aktywna:
        <input type="checkbox" name="active" checked><br><br>

        <input type="submit" name="add" value="Dodaj podstronę">
        <a href="admin.php" style="margin-left: 20px;">Powrót do listy</a>
    </form>';
}

function UsunPodstrone()
{
    global $link;

    if (!isset($_GET['id'])) return "Brak ID";

    $id = (int)$_GET['id'];

    $query = "DELETE FROM page_list WHERE id=$id LIMIT 1";

    if (mysqli_query($link, $query)) {
        return "<p style='color:green;'>Podstrona została usunięta!</p>";
    } else {
        return "<p style='color:red;'>Błąd usuwania: " . mysqli_error($link) . "</p>";
    }
}

// GŁÓWNA LOGIKA
if (!AdminLogin()) {
    echo FormularzLogowania();
    exit;
}

echo "<h1>Panel administracyjny</h1>";
echo '<p><a href="../index.php">← Powrót do strony</a> | <a href="?logout">Wyloguj</a></p>';

// Obsługa wylogowania
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

if (!isset($_GET['admin'])) {
    echo ListaPodstron();
} else {
    switch ($_GET['admin']) {
        case "edit":
            echo EdytujPodstrone();
            break;
        case "add":
            echo DodajNowaPodstrone();
            break;
        case "delete":
            echo UsunPodstrone();
            echo '<br><a href="admin.php">← Powrót do listy podstron</a>';
            break;
        default:
            echo ListaPodstron();
    }
}
?>
