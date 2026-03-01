<?php
declare(strict_types=1);

if (isset($_GET['lang']) && !empty($_GET['lang'])) {
  $jazyk_value = $_GET['lang'];
} else {
  $jazyk_value = "cs";
}

$stranka_value = "ubytovani.php";
include 'texty.php';
?>

<!doctype html>
<head>
    <?php
        include 'hlava.php';
    ?>
</head>
<body>
    <div class="safe">
        <header class="topbar" aria-label="Hlavička">
            <?php
                include 'uvod.php';
            ?>
        </header>
        <main class="manual-main" id="manualMain" aria-label="Obsah manuálu">

            <!-- Sekce: každá má id = kotva -->
            <section class="manual-section" id="checkin" tabindex="-1">
              <h2><?php echo $poleTextu[8] ?? 'Nenašel jsem text'; ?></h2>
              <div><?php echo $poleTextu[9] ?? 'Nenašel jsem text'; ?></div>
              <br>
              <div><?php echo $poleTextu[10] ?? 'Nenašel jsem text'; ?></div>
            </section>

            <section class="manual-section" id="odpad" tabindex="-1">
              <h2><?php echo $poleTextu[11] ?? 'Nenašel jsem text'; ?></h2>
              <div><?php echo $poleTextu[12] ?? 'Nenašel jsem text'; ?></div>
              <br>
              <div><?php echo $poleTextu[13] ?? 'Nenašel jsem text'; ?></div>
            </section>

            <section class="manual-section" id="kuchyne" tabindex="-1">
              <h2><?php echo $poleTextu[14] ?? 'Nenašel jsem text'; ?></h2>
              <div><?php echo $poleTextu[15] ?? 'Nenašel jsem text'; ?></div>
            </section>

            <section class="manual-section" id="bezpecnost" tabindex="-1">
              <h2><?php echo $poleTextu[16] ?? 'Nenašel jsem text'; ?></h2>
              <div><?php echo $poleTextu[17] ?? 'Nenašel jsem text'; ?></div>
              <br>
              <div><?php echo $poleTextu[18] ?? 'Nenašel jsem text'; ?></div>
              <br>
              <div><?php echo $poleTextu[19] ?? 'Nenašel jsem text'; ?></div>
            </section>
        </main>
    </div>
</body>
</html>
