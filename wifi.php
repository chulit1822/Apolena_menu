<?php
declare(strict_types=1);

if (isset($_GET['lang']) && !empty($_GET['lang'])) {
  $jazyk_value = $_GET['lang'];
} else {
  $jazyk_value = "cs";
}

$stranka_value = "wifi.php";
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
            <section class="manual-section" id="heslo" tabindex="-1">
                <h2><?= $poleTextu[8] ?? 'Nemám text' ?></h2>
                <div>
                    <?= $poleTextu[9] ?? 'Nemám text' ?>
                    <strong><?= $poleTextu[10] ?? 'Nemám text' ?></strong>
                </div>
            </section>

            <section class="manual-section" id="SSID" tabindex="-1">
                <h2><?= $poleTextu[11] ?? 'Nemám text' ?></h2>
                <div>
                    <?= $poleTextu[12] ?? 'Nemám text' ?>
                    <strong><?= $poleTextu[13] ?? 'Nemám text' ?></strong>
                    <?= $poleTextu[14] ?? 'Nemám text' ?>
                    <strong><?= $poleTextu[15] ?? 'Nemám text' ?></strong>
                    <?= $poleTextu[16] ?? 'Nemám text' ?>
                </div>
            </section>

            <section class="manual-section" id="sdileni" tabindex="-1">
                <h2><?= $poleTextu[17] ?? 'Nemám text' ?></h2>
                <br>
                <div class="QR">
                    <div style="text-align: center;"><?= $poleTextu[18] ?? 'Nemám text' ?></div>
                    <div style="text-align: center;"><?= $poleTextu[19] ?? 'Nemám text' ?></div>
                    <div style="text-align: center;"><img src="objekty/qr_apolena_24g.png" width="150px" height="130px" alt="QR Apolena 2.4 GHz"/></div>
                    <div style="text-align: center;"><img src="objekty/qr_apolena_5g.png" width="150px" height="130px" alt="QR Apolena 5 GHz"/></div>
                </div>            
            </section>

        </main>
    </div>
</body>
</html>
