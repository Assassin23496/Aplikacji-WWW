<?php
// Skrypt do naprawy ścieżek w plikach HTML - uruchom JEDEN RAZ
$html_files = [
    'html/glowna.html',
    'html/ohobby.html',
    'html/bohaterowie.html',
    'html/przedmioty.html',
    'html/galeria.html',
    'html/kontakt.html',
    'html/filmy.html'
];

foreach($html_files as $file) {
    if(file_exists($file)) {
        $content = file_get_contents($file);

        // Usuń ../ z początku ścieżek
        $content = str_replace('src="../images/', 'src="images/', $content);
        $content = str_replace("src='../images/", "src='images/", $content);
        $content = str_replace('href="../CSS/', 'href="CSS/', $content);
        $content = str_replace('src="../JS/', 'src="JS/', $content);

        file_put_contents($file, $content);
        echo "Naprawiono: $file<br>";
    }
}

echo "<h3>Gotowe! Ścieżki zostały naprawione.</h3>";
echo "<p>Teraz obrazy powinny działać poprawnie przy przełączaniu stron przez index.php</p>";
?>
