<?php
// init_database.php
global $link;
include "cfg.php";

echo "<h2>Inicjalizacja bazy danych</h2>";

// Sprawdź połączenie
if ($link) {
    echo "✅ Połączenie z MySQL udane<br>";

    // Utwórz tabele
    include "create_tables.php";
    createDatabaseTables($link);

    echo "✅ Baza danych została zainicjalizowana!<br>";
    echo "<a href='index.php'>Przejdź do strony głównej</a>";
} else {
    echo " Błąd połączenia z bazą danych";
}

mysqli_close($link);
?>
