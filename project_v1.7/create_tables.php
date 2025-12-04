<?php


function createDatabaseTables($link)
{
    if (!$link) {
        echo "<b>Błąd: Brak połączenia z bazą danych.</b><br>";
        return;
    }


    $tables_sql = [

        "page_list" => "
            CREATE TABLE IF NOT EXISTS page_list (
                id           INT AUTO_INCREMENT PRIMARY KEY,
                page_title   VARCHAR(255) NOT NULL,
                page_content TEXT,
                active       TINYINT DEFAULT 1,
                created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ",

        "heroes" => "
            CREATE TABLE IF NOT EXISTS heroes (
                id          INT AUTO_INCREMENT PRIMARY KEY,
                name        VARCHAR(100) NOT NULL,
                role        ENUM('Carry', 'Support', 'Mid', 'Offlane', 'Jungle') NOT NULL,
                description TEXT,
                image_url   VARCHAR(255),
                abilities   TEXT,
                active      TINYINT DEFAULT 1
            )
        ",

        "items" => "
            CREATE TABLE IF NOT EXISTS items (
                id          INT AUTO_INCREMENT PRIMARY KEY,
                name        VARCHAR(100) NOT NULL,
                type        ENUM('Basic', 'Upgrade', 'Artifact', 'Secret', 'Consumable') NOT NULL,
                cost        INT,
                description TEXT,
                effects     TEXT,
                active      TINYINT DEFAULT 1
            )
        ",

        "gallery" => "
            CREATE TABLE IF NOT EXISTS gallery (
                id          INT AUTO_INCREMENT PRIMARY KEY,
                title       VARCHAR(255) NOT NULL,
                image_url   VARCHAR(255) NOT NULL,
                description TEXT,
                category    ENUM('Heroes', 'Items', 'Screenshots', 'Artwork') DEFAULT 'Screenshots',
                active      TINYINT DEFAULT 1,
                created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ",

        "contact_messages" => "
            CREATE TABLE IF NOT EXISTS contact_messages (
                id         INT AUTO_INCREMENT PRIMARY KEY,
                name       VARCHAR(100) NOT NULL,
                email      VARCHAR(255) NOT NULL,
                subject    VARCHAR(255),
                message    TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                status     ENUM('new', 'read', 'replied') DEFAULT 'new'
            )
        "
    ];

    /* ============================================================
       Tworzenie tabel
       ============================================================ */
    echo "<h3>Tworzenie tabel...</h3>";

    foreach ($tables_sql as $table_name => $sql) {

        if (mysqli_query($link, $sql)) {
            echo "✔ Utworzono tabelę <b>$table_name</b><br>";
        } else {
            echo " Błąd tworzenia tabeli <b>$table_name</b>: "
                . mysqli_error($link) . "<br>";
        }
    }

    /* ============================================================
       Przykładowe dane
       ============================================================ */
    insertSampleData($link);
}


/* ============================================================
   Wstawianie danych przykładowych
   ============================================================ */
function insertSampleData($link)
{
    $check = mysqli_query($link, "SELECT COUNT(*) AS cnt FROM page_list");
    $row   = mysqli_fetch_assoc($check);

    if ($row['cnt'] > 0) {
        echo "<br>ℹ Dane już istnieją — pomijam wstawianie przykładowych rekordów.<br>";
        return;
    }

    echo "<br><h3>Wstawianie przykładowych danych...</h3>";

    /* --- Podstrony --- */
    $pages = [
        [
            "O mnie",
            "<h2>O mnie</h2><p>Witaj na mojej stronie o Dota 2! Jestem pasjonatem tej gry od wielu lat.</p>"
        ],
        [
            "Moje projekty",
            "<h2>Moje projekty</h2><p>Tutaj znajdziesz informacje o moich projektach związanych z Dota 2.</p>"
        ]
    ];

    foreach ($pages as $p) {

        $title   = mysqli_real_escape_string($link, $p[0]);
        $content = mysqli_real_escape_string($link, $p[1]);

        mysqli_query(
            $link,
            "INSERT INTO page_list (page_title, page_content, active)
             VALUES ('$title', '$content', 1)"
        );
    }


    /* --- Bohaterowie --- */
    $heroes = [
        [
            "Morphling",
            "Carry",
            "Morphling to zwinny bohater typu agility.",
            "images/morphling.jpg",
            "Waveform, Adaptive Strike, Morph, Replicate"
        ],
        [
            "Dazzle",
            "Support",
            "Dazzle to bohater wspierający.",
            "images/dazzle.jpg",
            "Poison Touch, Shallow Grave, Shadow Wave, Bad Juju"
        ]
    ];

    foreach ($heroes as $h) {

        $name        = mysqli_real_escape_string($link, $h[0]);
        $role        = mysqli_real_escape_string($link, $h[1]);
        $desc        = mysqli_real_escape_string($link, $h[2]);
        $img         = mysqli_real_escape_string($link, $h[3]);
        $abilities   = mysqli_real_escape_string($link, $h[4]);

        mysqli_query(
            $link,
            "INSERT INTO heroes (name, role, description, image_url, abilities)
             VALUES ('$name', '$role', '$desc', '$img', '$abilities')"
        );
    }

    echo "✔ Dodano przykładowe dane!<br>";
}


/* ============================================================
   Automatyczne uruchomienie, jeśli plik jest includowany
   ============================================================ */
if (isset($link) && $link instanceof mysqli) {
    createDatabaseTables($link);
}
?>
