<?php
function txt($id) {
    global $poleTextu;
    return $poleTextu[$id] ?? "Text #$id chybí";
}

require 'objekty/config.php';

$servername = $config['db_host'];
$dbname     = $config['db_name'];
$username   = $config['db_user'];
$password   = $config['db_pass'];

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* =====================================================
       TEXTY (manuál)
    ===================================================== */
    $stmt = $pdo->prepare("
        SELECT cislo, text
        FROM texty
        WHERE stranka = :stranka
          AND jazyk   = :jazyk
    ");
    $stmt->execute([
        'stranka' => $stranka_value,
        'jazyk'   => $jazyk_value
    ]);

    $poleTextu = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $radek) {
        $poleTextu[$radek['cislo']] = $radek['text'];
    }

    /* =====================================================
       BĚŽKY – TRASY
    ===================================================== */
    $stmt = $pdo->query("
        SELECT
            id,
            nazev,
            delka,
            popis_trasy,
            nastoupano_m,
            naklesano_m,
            gpx_file
        FROM bezky_trasy
        ORDER BY id ASC
    ");
    $bezkyTrasy = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $bezkyId = isset($_GET['bezky_id']) ? (int)$_GET['bezky_id'] : 0;
    $bezkyDetail = null;

    if ($bezkyId > 0) {
      foreach ($bezkyTrasy as $row) {
        if ((int)$row['id'] === $bezkyId) {
          $bezkyDetail = $row;
          break;
        }
      }
    }

    } catch (PDOException $e) {
        // Nikdy neecho hostům – jen log
        error_log('DB chyba: ' . $e->getMessage());
        $poleTextu  = [];
        $bezkyTrasy = [];
    }
?>