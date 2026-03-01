<?php
declare(strict_types=1);

if (isset($_GET['lang']) && !empty($_GET['lang'])) {
  $jazyk_value = $_GET['lang'];
} else {
  $jazyk_value = "cs";
}

$stranka_value = "topeni.php";
include 'texty.php';
?>

<!doctype html>
<html>
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
            <section class="manual-section" id="info" tabindex="-1">
                <h2><?= $poleTextu[8] ?? 'Nemám text' ?></h2>
                <div>
                    <?= $poleTextu[9] ?? 'Nemám text' ?>
                    <br>
                     - <?= $poleTextu[10] ?? 'Nemám text' ?>
                    <br>
                     - <?= $poleTextu[11] ?? 'Nemám text' ?>
                    <br>
                    <br>
                    <?= $poleTextu[12] ?? 'Nemám text' ?>
                </div>
            </section>

            <section class="manual-section" id="program1" tabindex="-1">
                <h2><?= $poleTextu[13] ?? 'Nemám text' ?></h2>
                <table class = "programy">
                    <thead>
                        <tr>
                            <th colspan="2"><?= $poleTextu[16] ?? 'Nemám text' ?></th>
                            <th rowspan="2" style="vertical-align: middle;">
                            <?= $poleTextu[17] ?? 'Nemám text' ?>
                          </th>
                        </tr>
                        <tr>
                            <th><?= $poleTextu[18] ?? 'Nemám text' ?></th>
                            <th><?= $poleTextu[19] ?? 'Nemám text' ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $poleTextu[20] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[21] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[22] ?? 'Nemám text' ?>&nbsp;<?= $poleTextu[23] ?? 'Nemám text' ?></td>
                        </tr>
                        <tr>
                            <td><?= $poleTextu[24] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[25] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[26] ?? 'Nemám text' ?>&nbsp;<?= $poleTextu[23] ?? 'Nemám text' ?></td>
                        </tr>
                        <tr>
                            <td><?= $poleTextu[27] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[28] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[29] ?? 'Nemám text' ?>&nbsp;<?= $poleTextu[23] ?? 'Nemám text' ?></td>
                        </tr>
                        <tr>
                            <td><?= $poleTextu[30] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[31] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[32] ?? 'Nemám text' ?>&nbsp;<?= $poleTextu[23] ?? 'Nemám text' ?></td>
                        </tr>
                    </tbody>
                </table>
            </section>            


            <section class="manual-section" id="program2" tabindex="-1">
                <h2><?= $poleTextu[33] ?? 'Nemám text' ?></h2>
                <table class = "programy">
                    <thead>
                        <tr>
                            <th colspan="2"><?= $poleTextu[16] ?? 'Nemám text' ?></th>
                            <th rowspan="2" style="vertical-align: middle;">
                            <?= $poleTextu[14] ?? 'Nemám text' ?>
                          </th>
                        </tr>
                        <tr>
                            <th><?= $poleTextu[18] ?? 'Nemám text' ?></th>
                            <th><?= $poleTextu[19] ?? 'Nemám text' ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $poleTextu[34] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[35] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[36] ?? 'Nemám text' ?>&nbsp;<?= $poleTextu[23] ?? 'Nemám text' ?></td>
                        </tr>
                        <tr>
                            <td><?= $poleTextu[37] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[38] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[39] ?? 'Nemám text' ?>&nbsp;<?= $poleTextu[23] ?? 'Nemám text' ?></td>
                        </tr>
                        <tr>
                            <td><?= $poleTextu[40] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[41] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[42] ?? 'Nemám text' ?>&nbsp;<?= $poleTextu[23] ?? 'Nemám text' ?></td>
                        </tr>
                        <tr>
                            <td><?= $poleTextu[43] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[44] ?? 'Nemám text' ?></td>
                            <td><?= $poleTextu[45] ?? 'Nemám text' ?>&nbsp;<?= $poleTextu[23] ?? 'Nemám text' ?></td>
                        </tr>
                    </tbody>
                </table>
            </section>            

            <section class="manual-section" id="teplota" tabindex="-1">
                <h2><?= $poleTextu[46] ?? 'Nemám text' ?> </h2>
                <br>
                <div class = teplota>
                    <img src="objekty/hlavice.jpg" width="90" height="137" alt="regulační hlavice"/>
                    <div><?= $poleTextu[47] ?? 'Nemám text' ?></div>
                </div>
            </section>

            <section class="manual-section" id="tipy" tabindex="-1">
                <h2><?= $poleTextu[48] ?? 'Nemám text' ?> 💡</h2>
                <br>
                <div>
                    <div>
                        <strong><?= $poleTextu[49] ?? 'Nemám text' ?></strong>
                        <?= $poleTextu[50] ?? 'Nemám text' ?>
                    </div>
                    <div>
                        <strong><?= $poleTextu[51] ?? 'Nemám text' ?></strong>
                        <?= $poleTextu[52] ?? 'Nemám text' ?>
                    </div>
                    <div>
                        <strong><?= $poleTextu[53] ?? 'Nemám text' ?></strong>
                        <?= $poleTextu[54] ?? 'Nemám text' ?>
                    </div>
                    <br>
                </div>
            </section>

        </main>
  </div>
</body>
</html>
