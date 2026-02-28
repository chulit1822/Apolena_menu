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
            <button class="backbtn" id="backBtn" type="button" aria-label="Zpět">
                <?php echo $poleTextu[7] ?? 'Nenašel jsem text'; ?>
            </button>
        </header>

        <main class="manual-main" id="manualMain" aria-label="Obsah manuálu">

            <!-- Sekce: každá má id = kotva -->
            <section class="manual-section" id="info" tabindex="-1">
                <h2><?php echo $poleTextu[8] ?? 'Nenašel jsem text'; ?></h2>
                <div>
                    <?php echo $poleTextu[9] ?? 'Nenašel jsem text'; ?>
                    <br>
                     - <?php echo $poleTextu[10] ?? 'Nenašel jsem text'; ?>
                    <br>
                     - <?php echo $poleTextu[11] ?? 'Nenašel jsem text'; ?>
                    <br>
                    <br>
                    <?php echo $poleTextu[12] ?? 'Nenašel jsem text'; ?>
                </div>
            </section>

            <section class="manual-section" id="program1" tabindex="-1">
                <h2><?php echo $poleTextu[13] ?? 'Nenašel jsem text'; ?></h2>
                <table class = "programy">
                    <thead>
                        <tr>
                            <th colspan="2"><?php echo $poleTextu[16] ?? 'Nenašel jsem text'; ?></th>
                            <th rowspan="2" style="vertical-align: middle;">
                            <?php echo $poleTextu[17] ?? 'Nenašel jsem text'; ?>
                          </th>
                        </tr>
                        <tr>
                            <th><?php echo $poleTextu[18] ?? 'Nenašel jsem text'; ?></th>
                            <th><?php echo $poleTextu[19] ?? 'Nenašel jsem text'; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $poleTextu[20] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[21] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[22] ?? 'Nenašel jsem text'; ?>&nbsp;<?php echo $poleTextu[23] ?? 'Nenašel jsem text'; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $poleTextu[24] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[25] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[26] ?? 'Nenašel jsem text'; ?>&nbsp;<?php echo $poleTextu[23] ?? 'Nenašel jsem text'; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $poleTextu[27] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[28] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[29] ?? 'Nenašel jsem text'; ?>&nbsp;<?php echo $poleTextu[23] ?? 'Nenašel jsem text'; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $poleTextu[30] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[31] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[32] ?? 'Nenašel jsem text'; ?>&nbsp;<?php echo $poleTextu[23] ?? 'Nenašel jsem text'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </section>            


            <section class="manual-section" id="program2" tabindex="-1">
                <h2><?php echo $poleTextu[33] ?? 'Nenašel jsem text'; ?></h2>
                <table class = "programy">
                    <thead>
                        <tr>
                            <th colspan="2"><?php echo $poleTextu[16] ?? 'Nenašel jsem text'; ?></th>
                            <th rowspan="2" style="vertical-align: middle;">
                            <?php echo $poleTextu[14] ?? 'Nenašel jsem text'; ?>
                          </th>
                        </tr>
                        <tr>
                            <th><?php echo $poleTextu[18] ?? 'Nenašel jsem text'; ?></th>
                            <th><?php echo $poleTextu[19] ?? 'Nenašel jsem text'; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $poleTextu[34] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[35] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[36] ?? 'Nenašel jsem text'; ?>&nbsp;<?php echo $poleTextu[23] ?? 'Nenašel jsem text'; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $poleTextu[37] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[38] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[39] ?? 'Nenašel jsem text'; ?>&nbsp;<?php echo $poleTextu[23] ?? 'Nenašel jsem text'; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $poleTextu[40] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[41] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[42] ?? 'Nenašel jsem text'; ?>&nbsp;<?php echo $poleTextu[23] ?? 'Nenašel jsem text'; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $poleTextu[43] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[44] ?? 'Nenašel jsem text'; ?></td>
                            <td><?php echo $poleTextu[45] ?? 'Nenašel jsem text'; ?>&nbsp;<?php echo $poleTextu[23] ?? 'Nenašel jsem text'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </section>            

            <section class="manual-section" id="teplota" tabindex="-1">
                <h2><?php echo $poleTextu[46] ?? 'Nenašel jsem text'; ?> </h2>
                <br>
                <div class = teplota>
                    <img src="objekty/hlavice.jpg" width="90" height="137" alt="regulační hlavice"/>
                    <div><?php echo $poleTextu[47] ?? 'Nenašel jsem text'; ?></div>
                </div>
            </section>

            <section class="manual-section" id="tipy" tabindex="-1">
                <h2><?php echo $poleTextu[48] ?? 'Nenašel jsem text'; ?> 💡</h2>
                <br>
                <div>
                    <div>
                        <strong><?php echo $poleTextu[49] ?? 'Nenašel jsem text'; ?></strong>
                        <?php echo $poleTextu[50] ?? 'Nenašel jsem text'; ?>
                    </div>
                    <div>
                        <strong><?php echo $poleTextu[51] ?? 'Nenašel jsem text'; ?></strong>
                        <?php echo $poleTextu[52] ?? 'Nenašel jsem text'; ?>
                    </div>
                    <div>
                        <strong><?php echo $poleTextu[53] ?? 'Nenašel jsem text'; ?></strong>
                        <?php echo $poleTextu[54] ?? 'Nenašel jsem text'; ?>
                    </div>
                    <br>
                </div>
            </section>

        </main>
  </div>
</body>
</html>
