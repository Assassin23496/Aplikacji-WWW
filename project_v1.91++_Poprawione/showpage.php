<?php
declare(strict_types=1);



require_once __DIR__ . "/cfg.php";

function PokazPodstrone(int $id): string
{
    global $db;

    $repo = new PageRepository($db);
    $page = $repo->getActiveById((int)$id);

    if (!$page) {
        return "<p>Brak takiej strony lub jest nieaktywna.</p>";
    }

    return (string)$page['page_content'];
}
