<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
include "cfg.php";

// Funkcje CMS
function PokazPodstrone($id) {
    global $link;

    $id = (int)$id;
    $query = "SELECT * FROM page_list WHERE id=$id AND active=1 LIMIT 1";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return '<h2>'.$row['page_title'].'</h2>'.nl2br($row['page_content']);
    } else {
        return '<h2>Strona nie istnieje lub jest nieaktywna</h2>';
    }
}

// Funkcja do pobierania bohaterów z bazy
function PokazBohaterow() {
    global $link;

    $query = "SELECT * FROM heroes WHERE active=1 ORDER BY name ASC";
    $result = mysqli_query($link, $query);

    $html = '<section class="hero-list">';

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $html .= '
            <article class="hero-card">
                <figure>
                    <img src="'.$row['image_url'].'" alt="'.$row['name'].'">
                    <figcaption>'.$row['name'].' — rola: '.$row['role'].'</figcaption>
                </figure>
                <h3>'.$row['name'].'</h3>
                <p>'.$row['description'].'</p>
                <p><strong>Umiejętności:</strong> '.$row['abilities'].'</p>
            </article>';
        }
    } else {
        $html .= '<p>Brak danych o bohaterach w bazie.</p>';
    }

    $html .= '</section>';
    return $html;
}

// Funkcja do pobierania przedmiotów z bazy
function PokazPrzedmioty() {
    global $link;

    $query = "SELECT * FROM items WHERE active=1 ORDER BY cost DESC";
    $result = mysqli_query($link, $query);

    $html = '<section>';
    $html .= '<h2>Przedmioty z bazy danych</h2>';

    if ($result && mysqli_num_rows($result) > 0) {
        $html .= '<table><tr><th>Nazwa</th><th>Typ</th><th>Koszt</th><th>Efekt</th></tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            $html .= '<tr>
                <td><strong>'.$row['name'].'</strong></td>
                <td>'.$row['type'].'</td>
                <td>'.$row['cost'].' złota</td>
                <td>'.$row['effects'].'</td>
            </tr>';
        }

        $html .= '</table>';
    } else {
        $html .= '<p>Brak danych o przedmiotach w bazie.</p>';
    }

    $html .= '</section>';
    return $html;
}

// Funkcja do pobierania galerii z bazy
function PokazGaleria() {
    global $link;

    $query = "SELECT * FROM gallery WHERE active=1 ORDER BY created_at DESC";
    $result = mysqli_query($link, $query);

    $html = '<section class="gallery-grid" aria-label="Galeria obrazów">';

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $html .= '
            <figure>
                <img src="'.$row['image_url'].'" alt="'.$row['title'].'">
                <figcaption>'.$row['title'].'</figcaption>
            </figure>';
        }
    } else {
        // Fallback do statycznych obrazów
        $html .= '
        <figure><img src="images/windranger.avif" alt="Windranger"><figcaption>Windranger</figcaption></figure>
        <figure><img src="images/drow_ranger.jpg" alt="Drow Ranger"><figcaption>Drow Ranger</figcaption></figure>
        <figure><img src="images/queen_of_pain.jpg" alt="Queen of Pain"><figcaption>Queen of Pain</figcaption></figure>';
    }

    $html .= '</section>';
    return $html;
}

// Funkcja do zapisywania wiadomości kontaktowych
function ZapiszWiadomosc($name, $email, $subject, $message) {
    global $link;

    $name = mysqli_real_escape_string($link, $name);
    $email = mysqli_real_escape_string($link, $email);
    $subject = mysqli_real_escape_string($link, $subject);
    $message = mysqli_real_escape_string($link, $message);

    $query = "INSERT INTO contact_messages (name, email, subject, message)
              VALUES ('$name', '$email', '$subject', '$message')";

    return mysqli_query($link, $query);
}

// Pozostały kod...
?>
