<?php
declare(strict_types=1);

/* =========================================================
   ZÁKLAD
========================================================= */
$jazyk_value   = (isset($_GET['lang']) && $_GET['lang'] !== '') ? $_GET['lang'] : 'cs';
$stranka_value = "lyze.php";

function h($s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function fmtTs($ts): string {
    if (!$ts) return '';
    return date('d.m.Y H:i', (int)$ts);
}

/* =========================================================
   DB: texty + běžky trasy
   (NEPOUŽÍVÁME include texty.php, protože ti to dřív nullovalo $pdo)
========================================================= */
require 'objekty/config.php';

$servername = $config['db_host'];
$dbname     = $config['db_name'];
$username   = $config['db_user'];
$password   = $config['db_pass'];

$poleTextu  = [];
$bezkyTrasy = [];

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Texty pro stránku + jazyk
    $stmt = $pdo->prepare("
        SELECT cislo, text
        FROM texty
        WHERE stranka = :stranka AND jazyk = :jazyk
    ");
    $stmt->execute(['stranka' => $stranka_value, 'jazyk' => $jazyk_value]);

    foreach ($stmt->fetchAll() as $radek) {
        $poleTextu[(int)$radek['cislo']] = $radek['text'];
    }

    // Běžky trasy (včetně GPX)
    $stmt = $pdo->prepare("
        SELECT
            id, nazev, delka, popis_trasy,
            nastoupano_m, naklesano_m, gpx_file
        FROM bezky_trasy
        WHERE jazyk = :jazyk
    ");
    $stmt->execute(['jazyk' => $jazyk_value]);
    $bezkyTrasy = $stmt->fetchAll();

} catch (Throwable $e) {
    error_log("lyze.php DB error: " . $e->getMessage());
    $poleTextu  = [];
    $bezkyTrasy = [];
}

/* =========================================================
   CACHE: lyže Klínovec (json)
========================================================= */
$CACHE_FILE = __DIR__ . '/objekty/cache/lyze_klinovec.json';

$data = null;
$err  = null;

if (!is_file($CACHE_FILE)) {
    $err = 'Data nejsou zatím k dispozici (cron ještě nevytvořil cache).';
} else {
    $json = @file_get_contents($CACHE_FILE);
    $data = $json ? json_decode($json, true) : null;
    if (!is_array($data)) {
        $err = 'Nepodařilo se načíst data (neplatný JSON).';
        $data = [];
    }
}

if (!is_array($data)) $data = [];

$akt  = $data['aktualne'] ?? [];
$tep  = $data['teploty']  ?? [];
$meta = $data['_meta']    ?? [];

/* =========================================================
   DETAIL TRASY: ?bezky_id=...
========================================================= */
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

/* =========================================================
   DETAIL VIEW (v rámci stejného souboru)
========================================================= */
if ($bezkyDetail) {
    // base path pro správný odkaz na /ap_test4/menu/gpx/...
    $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    $gpxFile  = basename((string)($bezkyDetail['gpx_file'] ?? ''));
    $gpxUrl   = ($gpxFile !== '') ? ($basePath . '/gpx/' . rawurlencode($gpxFile)) : '';

    include __DIR__ . '/partials/bezky_detail.php';
    exit;
}

/* =========================================================
   STANDARD VIEW (celý manuál)
========================================================= */
?>
<!doctype html>
<html>
<head>
    <?php include 'hlava.php'; ?>
</head>

<body>
<div class="safe">
    <header class="topbar" aria-label="Hlavička">
        <?php include 'uvod.php'; ?>
        <button class="backbtn" id="backBtn" type="button" aria-label="Nenašel jsem text">
            <?php echo $poleTextu[7] ?? 'Nenašel jsem text'; ?>
        </button>
    </header>

    <main class="manual-main" id="manualMain" aria-label="Obsah manuálu">

        <!-- SEKCE LYŽE -->
        <section class="manual-section lyze" id="lyze" tabindex="-1">

            <h2 class="lyze-title">
                <?php echo $poleTextu[8] ?? 'Nenašel jsem text'; ?>
                <?php if (!empty($meta['fetched_at'])): ?>
                    <span style="opacity:.7;font-weight:400">
                        (<?php echo h(fmtTs($meta['fetched_at'])); ?>)
                    </span>
                <?php endif; ?>
            </h2>

            <!-- KARTA 1: AKTUÁLNĚ -->
            <div class="lyze-card" aria-label="Aktuálně z Klínovce">
                <?php if ($err): ?>
                    <div class="lyze-row">
                        <span class="lyze-icon">⚠️</span>
                        <span class="lyze-label"><?php echo h($err); ?></span>
                    </div>
                    <div class="lyze-row" style="opacity:.7">
                        <span class="lyze-icon">📄</span>
                        <span class="lyze-label">Soubor:</span>
                        <span class="lyze-value"><?php echo h(basename($CACHE_FILE)); ?></span>
                    </div>
                <?php else: ?>

                    <div class="lyze-row">
                        <span class="lyze-icon">❄️</span>
                        <span class="lyze-label"><?php echo $poleTextu[10] ?? 'Sníh'; ?></span>
                        <span class="lyze-value"><?php echo h($akt['snow_cm'] ?? '—'); ?></span>
                    </div>

                    <div class="lyze-row">
                        <span class="lyze-icon">🧊</span>
                        <span class="lyze-label"><?php echo $poleTextu[11] ?? 'Typ sněhu'; ?></span>
                        <span class="lyze-value"><?php echo h($akt['snow_type'] ?? '—'); ?></span>
                    </div>

                    <div class="lyze-row">
                        <span class="lyze-icon">⏱️</span>
                        <span class="lyze-label"><?php echo $poleTextu[12] ?? 'Otevřeno'; ?></span>
                        <span class="lyze-value"><?php echo h($akt['status_hours'] ?? '—'); ?></span>
                    </div>

                    <div class="lyze-row">
                        <span class="lyze-icon">🌙</span>
                        <span class="lyze-label"><?php echo $poleTextu[13] ?? 'Noční'; ?></span>
                        <span class="lyze-value"><?php echo h($akt['night_ski'] ?? '—'); ?></span>
                    </div>

                    <div class="lyze-row">
                        <span class="lyze-icon">🚡</span>
                        <span class="lyze-label"><?php echo $poleTextu[14] ?? 'Lanovky'; ?></span>
                        <span class="lyze-value"><?php echo h($akt['lifts_open'] ?? '—'); ?></span>
                    </div>

                    <div class="lyze-row">
                        <span class="lyze-icon">🎿</span>
                        <span class="lyze-label"><?php echo $poleTextu[15] ?? 'Sjezdovky'; ?></span>
                        <span class="lyze-value"><?php echo h($akt['slopes_open'] ?? '—'); ?></span>
                    </div>

                <?php endif; ?>
            </div>

            <!--  KARTA 2: TEPLOTY V AREÁLU  -->
            <div class="lyze-card" aria-label="Teploty na Klínovci">
                <?php if ($err): ?>
                    <div class="lyze-row">
                        <span class="lyze-icon">⚠️</span>
                        <span class="lyze-label"><?php echo $poleTextu[16] ?? 'Teploty nejsou k dispozici.'; ?></span>
                    </div>
                <?php else: ?>

                    <table class="areal-teploty" aria-label="Teploty podle stanice">
                        <thead>
                        <tr>
                            <th><?php echo $poleTextu[17] ?? 'Stanice'; ?></th>
                            <th><?php echo $poleTextu[18] ?? 'Teplota'; ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?php echo $poleTextu[19] ?? 'Vrchol'; ?></td>
                            <td><span class="lyze-value"><?php echo h($tep['top_cinestar'] ?? '—'); ?> °C</span></td>
                        </tr>
                        <tr>
                            <td><?php echo $poleTextu[20] ?? 'Střed'; ?></td>
                            <td><span class="lyze-value"><?php echo h($tep['mid'] ?? '—'); ?> °C</span></td>
                        </tr>
                        <tr>
                            <td><?php echo $poleTextu[21] ?? 'Spodek'; ?></td>
                            <td><span class="lyze-value"><?php echo h($tep['bottom_cinestar'] ?? '—'); ?> °C</span></td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="lyze-row">
                        <span class="lyze-icon">📏</span>
                        <span class="lyze-label">Otevřeno:</span>
                        <span class="lyze-value"><?php echo h($akt['km_open'] ?? '—'); ?></span>
                    </div>

                    <?php if (!empty($meta['source'])): ?>
                        <div class="lyze-row" style="opacity:.75">
                            <span class="lyze-icon">🔗</span>
                            <span class="lyze-label">Zdroj:</span>
                            <span class="lyze-value"><?php echo h($meta['source']); ?></span>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>
            </div>

        </section>

        <!-- SEKCE SKIBUS -->
        <section class="manual-section lyze2" id="skibus" tabindex="-1">

            <h2><?php echo $poleTextu[22] ?? 'Skibus'; ?></h2>

            <div><?php echo $poleTextu[23] ?? ''; ?></div>
            <div class="skibus_rad"><?php echo $poleTextu[24] ?? ''; ?></div>

            <table class="sedm-sloupcu">
                <colgroup>
                    <col class="col-double">
                    <col class="col-single"><col class="col-single"><col class="col-single">
                    <col class="col-single"><col class="col-single"><col class="col-single">
                </colgroup>
                <tbody>
                <tr>
                    <td><?php echo $poleTextu[25] ?? ''; ?></td>
                    <td><?php echo $poleTextu[26] ?? ''; ?></td>
                    <td><?php echo $poleTextu[27] ?? ''; ?></td>
                    <td><?php echo $poleTextu[28] ?? ''; ?></td>
                    <td><?php echo $poleTextu[29] ?? ''; ?></td>
                    <td><?php echo $poleTextu[30] ?? ''; ?></td>
                    <td><?php echo $poleTextu[31] ?? ''; ?></td>
                </tr>
                <tr>
                    <td><?php echo $poleTextu[32] ?? ''; ?></td>
                    <td><?php echo $poleTextu[33] ?? ''; ?></td>
                    <td><?php echo $poleTextu[34] ?? ''; ?></td>
                    <td><?php echo $poleTextu[35] ?? ''; ?></td>
                    <td><?php echo $poleTextu[36] ?? ''; ?></td>
                    <td><?php echo $poleTextu[37] ?? ''; ?></td>
                    <td><?php echo $poleTextu[38] ?? ''; ?>C</td>
                </tr>
                </tbody>
            </table>

            <div class="skibus_rad"><?php echo $poleTextu[39] ?? ''; ?></div>

            <table class="sedm-sloupcu">
                <colgroup>
                    <col class="col-double">
                    <col class="col-single"><col class="col-single"><col class="col-single">
                    <col class="col-single"><col class="col-single"><col class="col-single">
                </colgroup>
                <tbody>
                <tr>
                    <td><?php echo $poleTextu[40] ?? ''; ?></td>
                    <td><?php echo $poleTextu[41] ?? ''; ?></td>
                    <td><?php echo $poleTextu[42] ?? ''; ?></td>
                    <td><?php echo $poleTextu[43] ?? ''; ?></td>
                    <td></td><td></td><td></td>
                </tr>
                <tr>
                    <td><?php echo $poleTextu[44] ?? ''; ?></td>
                    <td><?php echo $poleTextu[45] ?? ''; ?></td>
                    <td><?php echo $poleTextu[46] ?? ''; ?></td>
                    <td><?php echo $poleTextu[47] ?? ''; ?></td>
                    <td></td><td></td><td></td>
                </tr>
                </tbody>
            </table>

        </section>

        <!-- SEKCE BĚŽKY -->
        <section class="manual-section lyze2" id="bezky" tabindex="-1">

            <h2 class="lyze-title"><?php echo $poleTextu[48] ?? 'Nenašel jsem text'; ?></h2>

            <div class="lyze-card" aria-label="Doporučené trasy (rekreace / rodiny)">

                <?php if (empty($bezkyTrasy)): ?>
                    <div class="lyze-row">
                        <span class="lyze-icon">⚠️</span>
                        <span class="lyze-label"><?php echo $poleTextu[49] ?? 'Nenašel jsem text'; ?></span>
                    </div>
                <?php else: ?>
                    <table class="bezky-table" aria-label="Seznam běžkařských tras" style="width:100%; border-collapse:collapse">
                    <thead>
                        <tr style="opacity:.75">
                            <th style="text-align:left; padding:8px"><?php echo $poleTextu[50] ?? 'Nenašel jsem text'; ?></th>
                            <th style="text-align:left; padding:8px"><?php echo $poleTextu[51] ?? 'Nenašel jsem text'; ?></th>
                            <th style="text-align:left; padding:8px"><?php echo $poleTextu[52] ?? 'Nenašel jsem text'; ?></th>
                            <th style="text-align:left; padding:8px"><?php echo $poleTextu[53] ?? 'Nenašel jsem text'; ?></th>
                            <th style="text-align:left; padding:8px"><?php echo $poleTextu[54] ?? 'Nenašel jsem text'; ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($bezkyTrasy as $t): 
                            $detailHref = 'lyze.php?lang='.urlencode($jazyk_value).'&bezky_id='.(int)$t['id'].'#bezky-detail';
                        ?>
                          <tr class="bezky-row"
                              tabindex="0"
                              role="link"
                              aria-label="Otevřít detail trasy <?php echo h($t['nazev']); ?>"
                              data-href="<?php echo h($detailHref); ?>"
                              style="border-top:1px solid rgba(255,255,255,.10); cursor:pointer;">
                            <td style="padding:8px; font-weight:600">📍 <?php echo h($t['nazev']); ?></td>
                            <td style="padding:8px">📏 <?php echo h($t['delka']); ?></td>
                            <td style="padding:8px">⬆️ <?php echo (int)$t['nastoupano_m']; ?></td>
                            <td style="padding:8px">⬇️ <?php echo (int)$t['naklesano_m']; ?></td>
                            <td style="padding:8px">
                                <a href="<?php echo h($detailHref); ?>" class="bezky-link" tabindex="-1" >
                                   🗺️ Detail
                                </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>

            </div>

        </section>

    </main>
</div>

<script>
(function(){
  const rows = Array.from(document.querySelectorAll('.bezky-row[data-href]'));
  if (!rows.length) return;

  function openRow(row){
    const href = row.dataset.href;
    if (href) window.location.href = href;
  }

  function focusRow(i){
    if (i < 0) i = 0;
    if (i >= rows.length) i = rows.length - 1;
    rows[i].focus();
  }

  // aby po příchodu na #bezky byl fokus na prvním řádku
  if (location.hash === '#bezky') {
    setTimeout(() => focusRow(0), 0);
  }

  rows.forEach((row, idx) => {
    row.addEventListener('click', (e) => {
      // klik na odkaz necháme být
      if (e.target && e.target.closest && e.target.closest('a')) return;
      openRow(row);
    });

    row.addEventListener('keydown', (e) => {
      const k = e.key;
      const code = e.keyCode || 0;

      // OK na Android TV bývá keyCode 23 (DPAD_CENTER)
      const isOk = (k === 'Enter' || k === ' ' || code === 13 || code === 23);

      // Šipky: na TV často používají šipky k navigaci; přepneme řádek
      const isDown = (k === 'ArrowDown' || code === 40 || k === 'ArrowRight' || code === 39);
      const isUp   = (k === 'ArrowUp'   || code === 38 || k === 'ArrowLeft'  || code === 37);

      if (isOk) {
        e.preventDefault();
        openRow(row);
        return;
      }

      if (isDown) {
        e.preventDefault();
        focusRow(idx + 1);
        return;
      }

      if (isUp) {
        e.preventDefault();
        focusRow(idx - 1);
        return;
      }
    });
  });
})();
</script>

</body>
</html>
