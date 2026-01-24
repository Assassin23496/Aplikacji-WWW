<?php
declare(strict_types=1);

require_once __DIR__ . "/cfg.php";
require_once __DIR__ . "/showpage.php";

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

class Router
{
    /** @return array{type:string, id?:int} */
    public function resolve(string $alias): array
    {
        $map = [
            'start' => 1,
            'ohobby' => 2,
            'bohaterowie' => 3,
            'galeria' => 4,
            'filmy' => 5,
        ];

        if ($alias === 'kontakt') {
            return ['type' => 'contact'];
        }

        return ['type' => 'page', 'id' => $map[$alias] ?? 1];
    }
}

$alias = isset($_GET['idp']) ? (string)$_GET['idp'] : 'start';
$route = (new Router())->resolve($alias);

if ($route['type'] === 'contact') {
    include __DIR__ . "/contact.php";
    echo PokazKontakt();
    WyslijMailKontakt();
    exit();
}

$idp = (int)($route['id'] ?? 1);
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
                <li><a href="shop.php">Sklep</a></li>
            </ul>
        </nav>

    </div>
</header>

<main class="container">
    <?php
    echo PokazPodstrone($idp);
    ?>
</main>

<footer class="site-footer">
    <div class="container">
        <p>© <?php echo date('Y'); ?> DOTA2fan</p>
        <p>Autor: Raman Vaitsiuk 175529 grupa 3</p>
    </div>
</footer>

</body>
</html>
