<?php
declare(strict_types=1);

if (isset($_GET['lang']) && !empty($_GET['lang'])) {
  $jazyk_value = $_GET['lang'];
} else {
  $jazyk_value = "cs";
}

$stranka_value = "lyze.php";
include 'texty.php';
?>

<?php
require_once __DIR__ . '/klinovec_fetch.php';
require_once __DIR__ . '/common.php';

$klinovec = null;
try {
    $klinovec = get_klinovec_aktualne(10); // cache 10 minut
} catch (Throwable $e) {
    $klinovec = null; // když to spadne, stránka stejně poběží dál
}

$teploty = null;
try {
    $teploty = get_klinovec_teploty(10);
} catch (Throwable $e) {
    $teploty = null;
}
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


            <!-- SEKCE Skiareál Klínovec  -->
            <section class="manual-section lyze" id="lyze" tabindex="-1">
                <h2 class="lyze-title"><?php echo $poleTextu[8] ?? 'Nenašel jsem text'; ?></h2>

                <!-- KARTA 1 -->
                <div class="lyze-card">
                    <?php if (!empty($klinovec)): ?>

                        <?php if (!empty($klinovec['updated'])): ?>
                            <div class="lyze-row">
                                <span class="lyze-label"><?php echo $poleTextu[9] ?? 'Nenašel jsem text'; ?></span>
                                <span class="lyze-value"><?php echo htmlspecialchars($klinovec['updated']); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($klinovec['snow_cm'])): ?>
                            <div class="lyze-row">
                                <span class="lyze-icon" aria-hidden="true">❄️</span>
                                <?php echo $poleTextu[10] ?? 'Nenašel jsem text'; ?>  
                                <span class="lyze-value"><?php echo htmlspecialchars($klinovec['snow_cm']); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($klinovec['snow_type'])): ?>
                            <div class="lyze-row">
                                <span class="lyze-icon" aria-hidden="true">
                                    <?php echo lyzeSnowTypeIcon($klinovec['snow_type'] ?? null); ?>
                                </span>
                                <?php echo $poleTextu[11] ?? 'Nenašel jsem text'; ?>  
                                <span class="lyze-value"><?php echo htmlspecialchars($klinovec['snow_type']); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($klinovec['lifts_open'] !== null): ?>
                            <div class="lyze-row">
                                <span class="lyze-icon" aria-hidden="true">🚡️️</span>
                                <?php echo $poleTextu[13] ?? 'Nenašel jsem text'; ?> 
                                <span class="lyze-value">
                                    <?php echo (int)$klinovec['lifts_open']; ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if ($klinovec['slopes_open'] !== null): ?>
                            <div class="lyze-row">
                                <span class="lyze-icon" aria-hidden="true">🎿️️</span>
                                <?php echo $poleTextu[14] ?? 'Nenašel jsem text'; ?> 
                                <span class="lyze-value">
                                    <?php echo (int)$klinovec['slopes_open']; ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    
                    <?php endif; ?>
                </div>

                <!-- KARTA 2 -->
                <div class="lyze-card">
                    <?php if (!empty($klinovec)): ?>

                        <div class="lyze-row">
                            <span class="lyze-icon" aria-hidden="true">🌡️</span>
                            <span class="lyze-label"><?php echo $poleTextu[18] ?? 'Nenašel jsem text'; ?></span>
                        </div>

                        <table class="areal-teploty">
                          <thead>
                            <tr>
                              <th><?php echo $poleTextu[19] ?? 'Nenašel jsem text'; ?></th>
                              <th><?php echo $poleTextu[20] ?? 'Nenašel jsem text'; ?></th>
                              <th><?php echo $poleTextu[21] ?? 'Nenašel jsem text'; ?></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>
                                <span class="lyze-value">
                                  <?php echo ($teploty && $teploty['top_cinestar'] !== null)
                                      ? htmlspecialchars((string)$teploty['top_cinestar']) . ' °C'
                                      : '—'; ?>
                                </span>
                              </td>
                              <td>
                                <span class="lyze-value">
                                  <?php echo ($teploty && $teploty['mid'] !== null)
                                      ? htmlspecialchars((string)$teploty['mid']) . ' °C'
                                      : '—'; ?>
                                </span>
                              </td>
                              <td>
                                <span class="lyze-value">
                                  <?php echo ($teploty && $teploty['bottom_cinestar'] !== null)
                                      ? htmlspecialchars((string)$teploty['bottom_cinestar']) . ' °C'
                                      : '—'; ?>
                                </span>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <?php if (!empty($klinovec['km_open'])): ?>
                            <div class="lyze-row">
                                <span class="lyze-icon" aria-hidden="true">📏️️</span>
                                <?php echo $poleTextu[15] ?? 'Nenašel jsem text'; ?> 
                                <span class="lyze-value">
                                    <?php echo htmlspecialchars($klinovec['km_open']); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>            
            </section>

            <!-- SEKCE Skibus -->
            <section class="manual-section" id="skibus" tabindex="-1">
                <h2><?php echo $poleTextu[22] ?? 'Nenašel jsem text'; ?></h2>
                <div>
                    <?php echo $poleTextu[23] ?? 'Nenašel jsem text'; ?>
                    <br>
                    <div class="skibus_rad">
                        <?php echo $poleTextu[24] ?? 'Nenašel jsem text'; ?>
                    </div>
                    <table class="sedm-sloupcu">
                        <colgroup>
                            <col class="col-double">
                            <col class="col-single">
                            <col class="col-single">
                            <col class="col-single">
                            <col class="col-single">
                            <col class="col-single">
                            <col class="col-single">
                        </colgroup>
                        <tbody>
                            <tr>
                                <td><?php echo $poleTextu[25] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[26] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[27] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[28] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[29] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[30] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[31] ?? 'Nenašel jsem text'; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $poleTextu[32] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[33] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[34] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[35] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[36] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[37] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[38] ?? 'Nenašel jsem text'; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="skibus_rad">
                        <?php echo $poleTextu[39] ?? 'Nenašel jsem text'; ?>
                    </div>
                    <table class="sedm-sloupcu">
                        <colgroup>
                            <col class="col-double">
                            <col class="col-single">
                            <col class="col-single">
                            <col class="col-single">
                            <col class="col-single">
                            <col class="col-single">
                            <col class="col-single">
                        </colgroup>
                        <tbody>
                            <tr>
                                <td><?php echo $poleTextu[40] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[41] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[42] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[43] ?? 'Nenašel jsem text'; ?></td>
                                <td>—</td>
                                <td>—</td>
                                <td>—</td>
                            </tr>
                            <tr>
                                <td><?php echo $poleTextu[44] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[45] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[46] ?? 'Nenašel jsem text'; ?></td>
                                <td><?php echo $poleTextu[47] ?? 'Nenašel jsem text'; ?></td>
                                <td>—</td>
                                <td>—</td>
                                <td>—</td>
                            </tr>
                        </tbody>
                  </table>
                </div>
            </section>

        </main>
  </div>
</body>
</html>
