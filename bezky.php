<?php
declare(strict_types=1);

/**
 * karta.php – Běžecké tratě (verze 1)
 * - formát jako Lyže (dlaždice → detail)
 * - cílovka: rekreační hosté, rodiny
 * - obsah: doporučené trasy + odkaz na aktuální stav úprav na Mapy.com (zimní mapa)
 * - styl: neutrální (manuálový)
 */

$jazyk_value = (isset($_GET['lang']) && $_GET['lang'] !== '') ? $_GET['lang'] : 'cs';

// pokud používáš DB texty:
$stranka_value = "karta.php";
if (file_exists(__DIR__ . '/texty.php')) {
  include __DIR__ . '/texty.php';
}

// Souřadnice Apolena (z tvých dat)
$APO_LAT = 50.4182608;
$APO_LON = 12.9965269;

// Odkaz na Mapy.com (zimní mapa) – vycentrováno na Apolenu + hledání
$mapyLink = "https://mapy.com/cs/zimni?x={$APO_LON}&y={$APO_LAT}&z=13&q=" . rawurlencode("upravené běžkařské trasy") . "&cat=1";

// Data tras – jednoduché pole, později klidně vytáhneš z DB
$trasy = [
  [
    'nazev' => 'Klínovec – Nové Město – krátký okruh',
    'delka' => '5–7 km (lze zkrátit)',
    'profil' => 'Převážně rovinaté / mírně zvlněné',
    'rodiny' => 'Ano',
    'nastup' => 'Klínovec (parkoviště) / Nové Město',
    'poznamka' => 'Široké úseky, vhodné pro klidnou jízdu.'
  ],
  [
    'nazev' => 'Krušnohorská magistrála – rodinný úsek',
    'delka' => 'dle volby (doporučeno 3–8 km tam a zpět)',
    'profil' => 'Mírné vlnění',
    'rodiny' => 'Ano (kratší úsek)',
    'nastup' => 'Boží Dar / Klínovec (dle aktuální situace a sněhu)',
    'poznamka' => 'Známá trasa, často navštěvovaná – ověřte úpravu v den výjezdu.'
  ],
  [
    'nazev' => 'Loučná – lesní okruhy',
    'delka' => '3–6 km',
    'profil' => 'Mírné stoupání, lesní úseky',
    'rodiny' => 'Spíše větší děti',
    'nastup' => 'Loučná pod Klínovcem',
    'poznamka' => 'Klidnější prostředí, příjemné za bezvětří.'
  ],
];

?><!doctype html>
<html lang="<?php echo htmlspecialchars($jazyk_value, ENT_QUOTES, 'UTF-8'); ?>">
<head>
  <?php if (file_exists(__DIR__ . '/hlava.php')) { include __DIR__ . '/hlava.php'; } ?>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Běžecké tratě v okolí</title>

  <style>
    /* Minimal CSS jen pro tuto stránku (když už máš globální styly, klidně smaž) */
    .safe{max-width:1100px;margin:0 auto;padding:12px;}
    .topbar{display:flex;align-items:center;justify-content:space-between;gap:12px;}
    .backbtn{padding:10px 14px;border:1px solid rgba(255,255,255,.15);border-radius:12px;background:rgba(255,255,255,.06);color:inherit}
    .manual-main{margin-top:12px}
    .card{border:1px solid rgba(255,255,255,.12);border-radius:16px;padding:12px;background:rgba(255,255,255,.04)}
    .h1{font-size:1.2rem;margin:0 0 6px 0}
    .muted{opacity:.8}
    .grid{display:grid;grid-template-columns:repeat(12,1fr);gap:12px}
    .span-12{grid-column:span 12}
    .span-6{grid-column:span 6}
    .span-4{grid-column:span 4}
    @media (max-width:900px){.span-6,.span-4{grid-column:span 12}}

    .chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px}
    .chip{border:1px solid rgba(255,255,255,.12);border-radius:999px;padding:6px 10px;background:rgba(255,255,255,.03);font-size:.9rem}
    .btnlink{display:inline-flex;align-items:center;gap:8px;padding:10px 12px;border-radius:12px;border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.06);text-decoration:none;color:inherit}
    .btnrow{display:flex;flex-wrap:wrap;gap:10px;margin-top:10px}
    .hr{height:1px;background:rgba(255,255,255,.10);margin:12px 0}

    .tbl{width:100%;border-collapse:collapse;table-layout:fixed}
    .tbl th,.tbl td{border:1px solid rgba(255,255,255,.12);padding:8px;vertical-align:top}
    .tbl th{font-weight:600;opacity:.9}
    .tbl td{overflow:hidden;text-overflow:ellipsis}
    .small{font-size:.92rem;line-height:1.35}
  </style>
</head>

<body>
  <div class="safe">
    <header class="topbar" aria-label="Hlavička">
      <?php if (file_exists(__DIR__ . '/uvod.php')) { include __DIR__ . '/uvod.php'; } ?>

      <button class="backbtn" type="button" onclick="history.back()" aria-label="Zpět">
        Zpět
      </button>
    </header>

    <main class="manual-main" aria-label="Obsah karty">
      <!-- DLAŽDICE / HLAVIČKA KARTY -->
      <section class="card" aria-label="Běžky – úvod">
        <h1 class="h1">Běžecké tratě v okolí</h1>
        <div class="muted small">
          Doporučené trasy pro rekreační běžkování a rodiny. Volba konkrétní trasy závisí na sněhových podmínkách a aktuální úpravě stop.
        </div>

        <div class="chips" aria-label="Rychlé štítky">
          <div class="chip">🎿 běžky</div>
          <div class="chip">👨‍👩‍👧 rodiny</div>
          <div class="chip">📍 okolí Klínovce</div>
        </div>

        <div class="btnrow" aria-label="Akce">
          <a class="btnlink" href="<?php echo htmlspecialchars($mapyLink, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
            🗺️ Aktuální stav úpravy stop (Mapy.com)
          </a>
        </div>

        <div class="hr"></div>

        <div class="small muted">
          Úprava běžeckých tratí závisí na sněhových podmínkách a nelze ji garantovat. Aktuální stav stop doporučujeme ověřit v den výjezdu.
        </div>
      </section>

      <!-- DOPORUČENÉ TRASY -->
      <section class="grid" style="margin-top:12px" aria-label="Doporučené trasy">
        <div class="span-12 card">
          <h2 class="h1">Doporučené trasy</h2>
          <div class="muted small">Vybrané tipy pro klidné běžkování (vhodné i pro rodiny). Trasy berte orientačně – podle stavu stop se může lišit nejlepší nástup i směr.</div>
        </div>

        <?php foreach ($trasy as $t): ?>
          <article class="span-6 card" aria-label="<?php echo htmlspecialchars($t['nazev'], ENT_QUOTES, 'UTF-8'); ?>">
            <h3 class="h1" style="margin-bottom:8px"><?php echo htmlspecialchars($t['nazev'], ENT_QUOTES, 'UTF-8'); ?></h3>

            <table class="tbl small" aria-label="Parametry trasy">
              <tr>
                <th style="width:34%">Délka</th>
                <td><?php echo htmlspecialchars($t['delka'], ENT_QUOTES, 'UTF-8'); ?></td>
              </tr>
              <tr>
                <th>Profil</th>
                <td><?php echo htmlspecialchars($t['profil'], ENT_QUOTES, 'UTF-8'); ?></td>
              </tr>
              <tr>
                <th>Pro rodiny</th>
                <td><?php echo htmlspecialchars($t['rodiny'], ENT_QUOTES, 'UTF-8'); ?></td>
              </tr>
              <tr>
                <th>Nástup</th>
                <td><?php echo htmlspecialchars($t['nastup'], ENT_QUOTES, 'UTF-8'); ?></td>
              </tr>
              <tr>
                <th>Poznámka</th>
                <td><?php echo htmlspecialchars($t['poznamka'], ENT_QUOTES, 'UTF-8'); ?></td>
              </tr>
            </table>

            <div class="btnrow" style="margin-top:10px">
              <a class="btnlink" href="<?php echo htmlspecialchars($mapyLink, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
                Ověřit úpravu stop na Mapy.com
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </section>

      <!-- POZNÁMKA / BEZPEČNOST -->
      <section class="card" style="margin-top:12px" aria-label="Doporučení">
        <h2 class="h1">Doporučení</h2>
        <ul class="small">
          <li>Plánujte trasu podle aktuální úpravy stop a počasí.</li>
          <li>Pro rodiny s dětmi doporučujeme kratší okruhy a úseky s mírným profilem.</li>
          <li>V případě větru a námrazy mohou být hřebenové úseky náročnější.</li>
        </ul>
      </section>
    </main>

    <?php if (file_exists(__DIR__ . '/footer.php')) { include __DIR__ . '/footer.php'; } ?>
  </div>
</body>
</html>
