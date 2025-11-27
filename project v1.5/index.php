<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Dodaj base URL na początku
$base_url = ''; // Dla localhost w root
// Lub: $base_url = '/project1'; // Jeśli projekt w podkatalogu

/* --- Dynamiczne ładowanie podstron --- */
$idp = isset($_GET['idp']) ? $_GET['idp'] : 'glowna';

// Lista stron
$pages = [
    'glowna'      => 'html/glowna.html',
    'ohobby'      => 'html/ohobby.html',
    'bohaterowie' => 'html/bohaterowie.html',
    'przedmioty'  => 'html/przedmioty.html',
    'galeria'     => 'html/galeria.html',
    'kontakt'     => 'html/kontakt.html',
    'filmy'       => 'html/filmy.html'
];

$strona = isset($pages[$idp]) ? $pages[$idp] : 'html/404.html';

// Funkcja do naprawy ścieżek obrazów
function fixImagePaths($content, $base_url = '') {
    // Napraw ścieżki do obrazów
    $content = str_replace('src="images/', 'src="' . $base_url . 'images/', $content);
    $content = str_replace("src='images/", "src='" . $base_url . "images/", $content);

    // Napraw ścieżki do CSS i JS jeśli potrzeba
    $content = str_replace('href="CSS/', 'href="' . $base_url . 'CSS/', $content);
    $content = str_replace('src="JS/', 'src="' . $base_url . 'JS/', $content);

    return $content;
}

// Funkcja do ładowania i naprawiania zawartości strony
function loadAndFixPage($file_path, $base_url = '') {
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);
        return fixImagePaths($content, $base_url);
    }
    return '<h2>Nie znaleziono podstrony.</h2>';
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
    <link rel="stylesheet" href="<?php echo $base_url; ?>CSS/style.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>CSS/style1.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body class="<?php echo ($idp == '' || $idp == 'glowna') ? 'home' : 'subpage'; ?>">
<header class="site-header <?php echo ($idp != '' && $idp != 'glowna') ? 'small' : ''; ?>">
    <div class="container header-inner">
        <div class="logo"><a href="index.php">DOTA<span>2</span>fan</a></div>
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
    // Dynamiczne ładowanie zawartości strony z naprawionymi ścieżkami
    echo loadAndFixPage($strona, $base_url);
    ?>
</main>

<footer class="site-footer">
    <div class="container">
        <p>© <span id="year"><?php echo date('Y'); ?></span> DOTA2fan</p>

        <?php
        $nr_indeksu = '1234567';
        $nrGrupy = 'X';
        echo 'Autor: Raman Vaitsiuk '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
        ?>
    </div>
</footer>

<script src="<?php echo $base_url; ?>JS/timadate.js"></script>
<script src="<?php echo $base_url; ?>JS/kolorujtlo.js"></script>
<script>
    document.getElementById('year').textContent = new Date().getFullYear();

    <?php if (in_array($idp, ['', 'ohobby', 'galeria', 'kontakt', 'przedmioty'])): ?>
    window.onload = function() {
        if(typeof startclock !== 'undefined') startclock();
        if(typeof loadSavedBackground !== 'undefined') loadSavedBackground();
    };
    <?php endif; ?>
</script>
</body>
</html>


