<?php 
$jazyk_value = $_GET['lang'] ?? 'cs'; // PHP 7.0+
    $lang = $jazyk_value;
    $stranka_value = "index.php";
    
    include 'texty.php';
?>
<!doctype html>
<html lang="cs">
<head>
    <?php
        include 'hlava.php';
    ?>
</head>
<body>
    <!-------------                     ------------->
    <!------------- Panely - zobrazení  ------------->
    <!-------------                     ------------->

    <div class="safe">
        <header class="topbar" aria-label="Hlavička">
            <?php
                include 'uvod.php';
            ?>
        </header>

        <!-- Scrolluje jen obsah s dlaždicemi, hlavička zůstává vždy vidět -->
        <main class="main" id="main" aria-label="Obsah">
            <nav class="grid" id="grid" aria-label="Menu">
                <!-----              ----->
                <!--      DLAŽDICE      -->
                <!-----              ----->

                <!-- Panel manuál k ubytování -->
                <a class="tile span-2" href="#panel-ubytovani" data-panel="panel-ubytovani" tabindex="0">
                  <div class="emoji" aria-hidden="true">🏠</div>
                  <div>
                    <div class="title"><?= $poleTextu[9] ?? 'Nemám text' ?></div>
                    <div class="desc"><?= $poleTextu[10] ?? 'Nemám text' ?></div>
                  </div>
                </a>

                <!-- WiFi-->
                <a class="tile" href="#panel-wifi" data-panel="panel-wifi" tabindex="0">
                  <div class="emoji" aria-hidden="true">📶</div>
                  <div>
                    <div class="title"><?= $poleTextu[11] ?? 'Nemám text' ?></div>
                    <div class="desc"><?= $poleTextu[12] ?? 'Nemám text' ?></div>
                  </div>
                </a>

                <!-- Vytápění -->
                <a class="tile" href="#panel-topeni" data-panel="panel-topeni" tabindex="0">
                  <div class="emoji" aria-hidden="true">🔥</div>
                  <div>
                    <div class="title"><?= $poleTextu[13] ?? 'Nemám text' ?></div>
                    <div class="desc"><?= $poleTextu[14] ?? 'Nemám text' ?></div>
                  </div>
                </a>

                <!-- Počasí -->
                <a class="tile" href="#panel-pocasi" data-panel="panel-pocasi" tabindex="0">
                  <div class="emoji" aria-hidden="true">🌦️</div>
                    <div>
                        <div class="title"><?= $poleTextu[17] ?? 'Nemám text' ?></div>
                        <div class="desc"><?= $poleTextu[18] ?? 'Nemám text' ?></div>
                    </div>
                </a>

                <!-- Lyže -->
                <a class="tile" href="#panel-lyze" data-panel="panel-lyze" tabindex="0">
                  <div class="emoji" aria-hidden="true">🎿</div>
                  <div>
                    <div class="title"><?= $poleTextu[19] ?? 'Nemám text' ?></div>
                    <div class="desc"><?= $poleTextu[20] ?? 'Nemám text' ?></div>
                  </div>
                </a>

                <!-- Výlety -->
                <a class="tile" href="#panel-vylety" data-panel="panel-vylety" tabindex="0">
                  <div class="emoji" aria-hidden="true">🥾</div>
                  <div>
                    <div class="title"><?= $poleTextu[21] ?? 'Nemám text' ?></div>
                    <div class="desc"><?= $poleTextu[22] ?? 'Nemám text' ?></div>
                  </div>
                </a>

                <!-- Nákupy -->
                <a class="tile" href="#panel-nakupy" data-panel="panel-nakupy" tabindex="0">
                  <div class="emoji" aria-hidden="true">🛒</div>
                  <div>
                    <div class="title"><?= $poleTextu[23] ?? 'Nemám text' ?></div>
                    <div class="desc"><?= $poleTextu[24] ?? 'Nemám text' ?></div>
                  </div>
                </a>

                <!-- Kontakt -->
                <a class="tile" href="#panel-kontakt" data-panel="panel-kontakt" tabindex="0">
                  <div class="emoji" aria-hidden="true">☎️</div>
                  <div>
                    <div class="title"><?= $poleTextu[15] ?? 'Nemám text' ?></div>
                    <div class="desc"><?= $poleTextu[16] ?? 'Nemám text' ?></div>
                  </div>
                </a>
            </nav>
        </main>
    </div>

    <!-------------                ------------->
    <!------------- Panely - obsah ------------->
    <!-------------                ------------->

    <!-- Manuál k ubytování -->
    <section class="panel" id="panel-ubytovani" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Manuál k ubytování">
        <div class="box" role="document">
            <header class="panelhead">
                <button class="backbtn" data-close>
                    <?= $poleTextu[25] ?? 'Nemám text' ?>
                </button>
                <h2><?= $poleTextu[26] ?? 'Nemám text' ?></h2>
            </header>
            <div class="content">
                <div class="list">
                    <a class="item focusable" href="ubytovani.php#checkin" tabindex="0">
                        <h3><?= $poleTextu[27] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[28] ?? 'Nemám text' ?></p>
                    </a>
                    <a class="item focusable" href="ubytovani.php#odpad" tabindex="0">
                        <h3><?= $poleTextu[29] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[30] ?? 'Nemám text' ?></p>
                    </a>
                    <a class="item focusable" href="ubytovani.php#kuchyne" tabindex="0">
                        <h3><?= $poleTextu[31] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[32] ?? 'Nemám text' ?></p>
                    </a>
                    <a class="item focusable" href="ubytovani.php#bezpecnost" tabindex="0">
                        <h3><?= $poleTextu[33] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[34] ?? 'Nemám text' ?></p>
                    </a>
                </div>
            </div>
        </div>
    </section>
  
    <!-- WiFi -->
    <section class="panel" id="panel-wifi" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Wi‑Fi">
        <div class="box" role="document">
            <header class="panelhead">
                <button class="backbtn" data-close>
                    <?php echo $poleTextu[35] ?? 'Nenašel jsem text'; ?>
                </button>
                <h2><?php echo $poleTextu[36] ?? 'Nenašel jsem text'; ?></h2>
            </header>
            <div class="content">
                <div class="list">
                    <a class="item focusable" href="wifi.php#heslo" tabindex="0">
                        <h3><?php echo $poleTextu[37] ?? 'Nenašel jsem text'; ?></h3>
                        <p><?php echo $poleTextu[38] ?? 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="wifi.php#SSID" tabindex="0">
                        <h3><?php echo $poleTextu[93] ?? 'Nenašel jsem text'; ?></h3>
                        <p><?php echo $poleTextu[94] ?? 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="wifi.php#sdileni" tabindex="0">
                        <h3><?php echo $poleTextu[39] ?? 'Nenašel jsem text'; ?></h3>
                        <p><?php echo $poleTextu[40] ?? 'Nenašel jsem text'; ?></p>
                    </a>
                </div>
            </div>
          </div>
        </div>
    </section>

    <!-- Vytápění -->
    <section class="panel" id="panel-topeni" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Vytápění">
        <div class="box" role="document">
            <header class="panelhead">
                <button class="backbtn" data-close>
                    <?php echo $poleTextu[41] ?? 'Nenašel jsem text'; ?>
                </button>
                <h2><?php echo $poleTextu[42] ?? 'Nenašel jsem text'; ?></h2>
            </header>
            <div class="content">
                <div class="list">
                    <a class="item focusable" href="topeni.php#info" tabindex="0">
                        <h3><?php echo $poleTextu[43] ?? 'Nenašel jsem text'; ?></h3>
                        <p><?php echo $poleTextu[44] ?? 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="topeni.php#program1" tabindex="0">
                        <h3><?php echo $poleTextu[45] ?? 'Nenašel jsem text'; ?></h3>
                        <p><?php echo $poleTextu[46] ?? 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="topeni.php#program2" tabindex="0">
                        <h3><?php echo $poleTextu[95] ?? 'Nenašel jsem text'; ?></h3>
                        <p><?php echo $poleTextu[96] ?? 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="topeni.php#teplota" tabindex="0">
                        <h3><?php echo $poleTextu[97] ?? 'Nenašel jsem text'; ?></h3>
                        <p><?php echo $poleTextu[98] ?? 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="topeni.php#tipy" tabindex="0">
                        <h3><?php echo $poleTextu[99] ?? 'Nenašel jsem text'; ?> 💡</h3>
                        <p><?php echo $poleTextu[100] ?? 'Nenašel jsem text'; ?></p>
                    </a>
                </div>
              </div>
        </div>
    </section>
    
    <!-- Kontakt -->
    <section class="panel" id="panel-kontakt" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Kontakt">
        <div class="box" role="document">
            <header class="panelhead">
                <button class="backbtn" data-close>
                    <?= $poleTextu[47] ?? 'Nemám text' ?>
                </button>
                <h2><?= $poleTextu[48] ?? 'Nemám text' ?></h2>
            </header>
            <div class="content">
                <div class="list">
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?= $poleTextu[49] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[50] ?? 'Nemám text' ?></p>
                    </a>
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?= $poleTextu[51] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[52] ?? 'Nemám text' ?></p>
                    </a>
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?= $poleTextu[53] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[54] ?? 'Nemám text' ?></p>
                    </a>
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?= $poleTextu[55] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[56] ?? 'Nemám text' ?></p>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Počasí -->
    <section class="panel" id="panel-pocasi" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Počasí">
        <div class="box" role="document">
            <header class="panelhead">
                <button class="backbtn" data-close><?= $poleTextu[57] ?? 'Nemám text' ?></button>
                <h2><?= $poleTextu[58] ?? 'Nemám text' ?></h2>
            </header>
            <div class="content">
                <div class="list">
                    <a class="item focusable" href="pocasi.php?lang=<?= $jazyk_value ?>#dnes" tabindex="0">
                        <h3><?= $poleTextu[59] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[60] ?? 'Nemám text' ?></p>
                    </a>
                    <a class="item focusable" href="pocasi.php?lang=<?= $jazyk_value ?>#zitra" tabindex="0">
                        <h3><?= $poleTextu[61] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[62] ?? 'Nemám text' ?></p>
                    </a>
                    <a class="item focusable" href="pocasi.php?lang=<?= $jazyk_value ?>#vyhled" tabindex="0">
                        <h3><?= $poleTextu[63] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[64] ?? 'Nemám text' ?></p>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Lyže -->
    <section class="panel" id="panel-lyze" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Lyže">
        <div class="box" role="document">
          <header class="panelhead">
              <button class="backbtn" data-close>
                  <?= $poleTextu[67] ?? 'Nemám text' ?>
              </button>
              <h2><?= $poleTextu[68] ?? 'Nemám text' ?></h2>
          </header>
          <div class="content">
              <div class="list">
                  <a class="item focusable" href="lyze.php#lyze" tabindex="0">
                      <h3><?= $poleTextu[69] ?? 'Nemám text' ?></h3>
                      <p><?= $poleTextu[70] ?? 'Nemám text' ?></p>
                  </a>
                  <a class="item focusable" href="lyze.php#skibus" tabindex="0">
                      <h3><?= $poleTextu[71] ?? 'Nemám text' ?></h3>
                      <p><?= $poleTextu[72] ?? 'Nemám text' ?></p>
                  </a>
                  <a a class="item focusable" href="lyze.php?lang=<?php echo urlencode($jazyk_value); ?>#bezky" tabindex="0">
                      <h3><?= $poleTextu[73] ?? 'Nemám text' ?></h3>
                      <p><?= $poleTextu[74] ?? 'Nemám text' ?></p>
                  </a>
                  <a class="item focusable" href="#" tabindex="0">
                      <h3><?= $poleTextu[75] ?? 'Nemám text' ?></h3>
                      <p><?= $poleTextu[76] ?? 'Nemám text' ?></p>
                  </a>
              </div>
          </div>
        </div>
    </section>

    <!-- Výlety -->
    <section class="panel" id="panel-vylety" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Výlety">
        <div class="box" role="document">
            <header class="panelhead">
                <button class="backbtn" data-close>
                    <?= $poleTextu[77] ?? 'Nemám text' ?>
                </button>
                <h2><?= $poleTextu[78] ?? 'Nemám text' ?></h2>
            </header>
            <div class="content">
                <div class="list">
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?= $poleTextu[79] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[80] ?? 'Nemám text' ?></p>
                    </a>
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?= $poleTextu[81] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[82] ?? 'Nemám text' ?></p>
                    </a>
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?= $poleTextu[83] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[84] ?? 'Nemám text' ?></p>
                    </a>
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?= $poleTextu[85] ?? 'Nemám text' ?></h3>
                        <p><?= $poleTextu[86] ?? 'Nemám text' ?></p>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Nákupy -->
    <section class="panel" id="panel-nakupy" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Nákupy">
        <div class="box" role="document">
            <header class="panelhead">
                <button class="backbtn" data-close>← Zpět</button>
                <h2>Nákupy</h2>
            </header>
            <div class="content">
                <div class="list">
                    <a class="item focusable" href="#" tabindex="0"><h3>Obchody u nás</h3><p>Nejbližší obchody v Čechách</p></a>
                    <a class="item focusable" href="#" tabindex="0"><h3>Obchody v Něměcku</h3><p>Nejbližší obchody v Něměcku</p></a>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
