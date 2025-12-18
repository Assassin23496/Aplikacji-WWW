<?php
include("cfg.php");          // Połączenie z bazą danych
include("showpage.php");     // Funkcja PokazPodstrone()

// Ograniczenie komunikatów w środowisku lokalnym
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

/*
 * ------------------------------------------------------------
 * Pobranie parametru idp z adresu URL
 * np. index.php?idp=galeria
 * ------------------------------------------------------------
 */
$idp_alias = isset($_GET['idp']) ? $_GET['idp'] : 'start';

/*
 * ------------------------------------------------------------
 * Router CMS – mapowanie aliasów na ID stron w bazie danych
 * ------------------------------------------------------------
 */
switch ($idp_alias) {
    case 'start':        $idp = 1; break;
    case 'ohobby':       $idp = 2; break;
    case 'bohaterowie':  $idp = 3; break;
    case 'galeria':      $idp = 4; break;
    case 'filmy':        $idp = 5; break;
    case 'przedmioty':   $idp = 6; break;

    /*
     * Kontakt obsługiwany osobnym modułem (formularz + mail)
     */
    case 'kontakt':
        include("contact.php");
        echo PokazKontakt();
        WyslijMailKontakt();
        exit();

    default:
        $idp = 1;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>DOTA2fan — Moje hobby</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/style1.css">
</head>

<body>

<header class="site-header">
    <div class="container header-inner">

        <div class="logo">
            <a href="index.php">DOTA<span>2</span>fan</a>
        </div>

        <nav class="main-nav">
            <ul>
                <li><a href="index.php" <?= $idp_alias=='start'?'class="active"':'' ?>>Strona główna</a></li>
                <li><a href="index.php?idp=ohobby" <?= $idp_alias=='ohobby'?'class="active"':'' ?>>O hobby</a></li>
                <li><a href="index.php?idp=bohaterowie" <?= $idp_alias=='bohaterowie'?'class="active"':'' ?>>Bohaterowie</a></li>
                <li><a href="index.php?idp=przedmioty" <?= $idp_alias=='przedmioty'?'class="active"':'' ?>>Przedmioty</a></li>
                <li><a href="index.php?idp=galeria" <?= $idp_alias=='galeria'?'class="active"':'' ?>>Galeria</a></li>
                <li><a href="index.php?idp=kontakt" <?= $idp_alias=='kontakt'?'class="active"':'' ?>>Kontakt</a></li>
                <li><a href="index.php?idp=filmy" <?= $idp_alias=='filmy'?'class="active"':'' ?>>Filmy</a></li>
            </ul>
        </nav>

    </div>
</header>

<main class="container">
    <?php
    // Wyświetlenie treści strony z bazy danych
    echo PokazPodstrone($idp);
    ?>
</main>

<footer class="site-footer">
    <div class="container">
        <p>© <?php echo date('Y'); ?> DOTA2fan</p>
        <p>Autor: Raman Vaitsiuk 175529 grupa X</p>
    </div>
</footer>

</body>
</html>
