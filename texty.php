<?php
require 'objekty/config.php';
$servername = $config['db_host'];
$dbname = $config['db_name'];
$username = $config['db_user'];
$password = $config['db_pass'];

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Načtení textů pro konkrétní stránku a jazyk
    $stmt = $pdo->prepare("SELECT cislo, text FROM texty WHERE stranka = :stranka AND jazyk = :jazyk");
    
    // Vykonání dotazu s použitím parametrů
    $stmt->execute(['stranka' => $stranka_value, 'jazyk' => $jazyk_value]);
    
    // Načtení všech textů
    $texty = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Uložení textů do asociativního pole podle klíče
    $poleTextu = [];
    foreach ($texty as $radek) {
        $poleTextu[$radek['cislo']] = $radek['text'];
    }

} catch (PDOException $e) {
    echo "Chyba: " . $e->getMessage();
}

finally {
    // Uzavření připojení k databázi
    $pdo = null;
}
?>
