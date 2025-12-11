<?php
include("cfg.php");          // Dołączenie pliku konfiguracyjnego z połączeniem do bazy
include("showpage.php");     // Dołączenie pliku odpowiedzialnego za ładowanie podstron z bazy

// Wyłączenie części komunikatów, aby nie zakłócały działania strony w środowisku lokalnym
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

/* ------------------------------------------------------------
 *  Dynamiczne ładowanie podstron na podstawie parametru "idp"
 *  Pobieramy wartość z adresu URL (np. index.php?idp=galeria)
 *  Jeżeli brak parametru — ładujemy stronę główną
 * ------------------------------------------------------------ */
$idp = isset($_GET['idp']) ? $_GET['idp'] : '';

/* ------------------------------------------------------------
 *  Ręczne mapowanie aliasów na konkretne pliki HTML.
 *  Ten blok istniał wcześniej i służy do statycznych podstron.
 *  Jeżeli alias nie jest znany — wyświetlamy stronę błędu 404.
 * ------------------------------------------------------------ */
if($idp == '') $strona = 'html/glowna.html';
elseif($idp == 'ohobby') $strona = 'html/ohobby.html';
elseif($idp == 'bohaterowie') $strona = 'html/bohaterowie.html';
elseif($idp == 'przedmioty') $strona = 'html/przedmioty.html';
elseif($idp == 'galeria') $strona = 'html/galeria.html';
elseif($idp == 'kontakt') $strona = 'html/kontact.html';
elseif($idp == 'filmy') $strona = 'html/filmy.html';
else $strona = 'html/404.html';

// Sprawdzenie czy plik istnieje — zabezpieczenie przed błędami ścieżek
if (!file_exists($strona)) {
    $strona = 'html/404.html';
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="description" content="O moim hobby — Dota 2">
    <meta name="keywords" content="Dota2, historia, hobby, Raman Vaitsiuk">
    <meta name="author" content="Raman Vaitsiuk">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DOTA2fan — Moje hobby</title>

    <!-- Dołączenie arkuszy stylów -->
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/style1.css">

    <!-- Dołączenie biblioteki jQuery z CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<!-- Dynamiczne ustawienie klasy dla strony głównej i podstron -->
<body class="<?php echo ($idp == '' || $idp == 'glowna') ? 'home' : 'subpage'; ?>">

<header class="site-header <?php echo ($idp != '' && $idp != 'glowna') ? 'small' : ''; ?>">
    <div class="container header-inner">

        <!-- Logo strony -->
        <div class="logo"><a href="index.php">DOTA<span>2</span>fan</a></div>

        <!-- Główna nawigacja strony (menu) -->
        <nav class="main-nav" aria-label="Nawigacja główna">
            <ul>
                <li><a href="index.php" <?= $idp==''?'class="active"':'' ?>>Strona główna</a></li>
                <li><a href="index.php?idp=ohobby" <?= $idp=='ohobby'?'class="active"':'' ?>>O hobby</a></li>
                <li><a href="index.php?idp=bohaterowie" <?= $idp=='bohaterowie'?'class="active"':'' ?>>Bohaterowie</a></li>
                <li><a href="index.php?idp=przedmioty" <?= $idp=='przedmioty'?'class="active"':'' ?>>Przedmioty</a></li>
                <li><a href="index.php?idp=galeria" <?= $idp=='galeria'?'class="active"':'' ?>>Galeria</a></li>
                <li><a href="index.php?idp=kontakt" <?= $idp=='kontakt'?'class="active"':'' ?>>Kontakt</a></li>
                <li><a href="index.php?idp=filmy" <?= $idp=='filmy'?'class="active"':'' ?>>Filmy</a></li>
            </ul>
        </nav>
    </div>

    <!-- Sekcja HERO — wyświetlana tylko na stronie głównej -->
    <?php if ($idp == '' || $idp == 'glowna'): ?>
        <div class="hero">
            <div class="container hero-content">
                <h1>Moje hobby — Dota 2</h1>
                <p>Dlaczego Dota 2 to coś więcej niż gra — strategia, współpraca i ciągłe doskonalenie.</p>
                <a class="btn" href="index.php?idp=ohobby">Dowiedz się więcej</a>
            </div>
        </div>
    <?php endif; ?>
</header>

<main class="container">
    <?php
    /* ------------------------------------------------------------
     *  Router aliasów używany przez system CMS
     *  Mapa słów (np. "galeria") na numery ID podstron w bazie danych.
     *  Dzięki temu system może ładować treść dynamicznie z MySQL.
     * ------------------------------------------------------------ */
    $idp_alias = isset($_GET['idp']) ? $_GET['idp'] : 'start';

    switch ($idp_alias) {
        case 'start':        $idp = 1; break;
        case 'ohobby':       $idp = 2; break;
        case 'bohaterowie':  $idp = 3; break;
        case 'przedmioty':   $idp = 6; break;
        case 'galeria':      $idp = 4; break;

        /* --------------------------------------------------------
         *  Sekcja kontaktu — nie jest w bazie, dlatego obsługiwana
         *  przez osobne funkcje PHP. Po wysłaniu formularza
         *  wykonywana jest funkcja WyslijMailKontakt().
         * -------------------------------------------------------- */
        case 'kontakt':
            include("contact.php");
            echo PokazKontakt();       // Wyświetlenie formularza
            WyslijMailKontakt();       // Obsługa wysyłania wiadomości
            exit();

        case 'filmy':        $idp = 5; break;

        default:             $idp = 1; // domyślna strona
    }

    // Wyświetlenie wybranej podstrony przy pomocy CMS
    echo PokazPodstrone($idp);
    ?>
</main>

<footer class="site-footer">
    <div class="container">
        <p>© <span id="year"><?php echo date('Y'); ?></span> DOTA2fan</p>

        <?php
        // Informacje autora — wymagane w LAB
        $nr_indeksu = '1234567';
        $nrGrupy = 'X';
        echo 'Autor: Raman Vaitsiuk '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
        ?>
    </div>
</footer>

<!-- Skrypty JS -->
<script src="JS/timadate.js"></script>
<script src="JS/kolorujtlo.js"></script>

<script>
    // Ustawienie aktualnego roku w stopce
    document.getElementById('year').textContent = new Date().getFullYear();

    // Uruchamianie zegara i zapamiętywania tła na wybranych podstronach
    <?php if (in_array($idp, ['', 'ohobby', 'galeria', 'kontakt', 'przedmioty'])): ?>
    window.onload = function() {
        if(typeof startclock !== 'undefined') startclock();
        if(typeof loadSavedBackground !== 'undefined') loadSavedBackground();
    };
    <?php endif; ?>
</script>

</body>
</html>
