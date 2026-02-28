<?php 
    if (isset($_GET['lang']) && !empty($_GET['lang'])) {
        $jazyk_value = $_GET['lang'];
    } else {
        $jazyk_value = "cs";
        $lang = "cs";
    }

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
                    <div class="title"><?php echo isset($poleTextu[9]) ? $poleTextu[9] : 'Nenašel jsem text'; ?></div>
                    <div class="desc"><?php echo isset($poleTextu[10]) ? $poleTextu[10] : 'Nenašel jsem text'; ?></div>
                  </div>
                </a>

                <!-- WiFi-->
                <a class="tile" href="#panel-wifi" data-panel="panel-wifi" tabindex="0">
                  <div class="emoji" aria-hidden="true">📶</div>
                  <div>
                    <div class="title"><?php echo isset($poleTextu[11]) ? $poleTextu[11] : 'Nenašel jsem text'; ?></div>
                    <div class="desc"><?php echo isset($poleTextu[12]) ? $poleTextu[12] : 'Nenašel jsem text'; ?></div>
                  </div>
                </a>

                <!-- Vytápění -->
                <a class="tile" href="#panel-topeni" data-panel="panel-topeni" tabindex="0">
                  <div class="emoji" aria-hidden="true">🔥</div>
                  <div>
                    <div class="title"><?php echo isset($poleTextu[13]) ? $poleTextu[13] : 'Nenašel jsem text'; ?></div>
                    <div class="desc"><?php echo isset($poleTextu[14]) ? $poleTextu[14] : 'Nenašel jsem text'; ?></div>
                  </div>
                </a>

                <!-- Kontakt -->
                <a class="tile span-2" href="#panel-kontakt" data-panel="panel-kontakt" tabindex="0">
                  <div class="emoji" aria-hidden="true">☎️</div>
                  <div>
                    <div class="title"><?php echo isset($poleTextu[15]) ? $poleTextu[15] : 'Nenašel jsem text'; ?></div>
                    <div class="desc"><?php echo isset($poleTextu[16]) ? $poleTextu[16] : 'Nenašel jsem text'; ?></div>
                  </div>
                </a>

                <!-- Počasí -->
                <a class="tile" href="#panel-pocasi" data-panel="panel-pocasi" tabindex="0">
                  <div class="emoji" aria-hidden="true">🌦️</div>
                    <div>
                        <div class="title"><?php echo isset($poleTextu[17]) ? $poleTextu[17] : 'Nenašel jsem text'; ?></div>
                        <div class="desc"><?php echo isset($poleTextu[18]) ? $poleTextu[18] : 'Nenašel jsem text'; ?></div>
                    </div>
                </a>

                <!-- Lyže -->
                <a class="tile" href="#panel-lyze" data-panel="panel-lyze" tabindex="0">
                  <div class="emoji" aria-hidden="true">🎿</div>
                  <div>
                    <div class="title"><?php echo isset($poleTextu[19]) ? $poleTextu[19] : 'Nenašel jsem text'; ?></div>
                    <div class="desc"><?php echo isset($poleTextu[20]) ? $poleTextu[20] : 'Nenašel jsem text'; ?></div>
                  </div>
                </a>

                <!-- Výlety -->
                <a class="tile" href="#panel-vylety" data-panel="panel-vylety" tabindex="0">
                  <div class="emoji" aria-hidden="true">🥾</div>
                  <div>
                    <div class="title"><?php echo isset($poleTextu[21]) ? $poleTextu[21] : 'Nenašel jsem text'; ?></div>
                    <div class="desc"><?php echo isset($poleTextu[22]) ? $poleTextu[22] : 'Nenašel jsem text'; ?></div>
                  </div>
                </a>

                <!-- Nákupy -->
                <a class="tile" href="#panel-nakupy" data-panel="panel-nakupy" tabindex="0">
                  <div class="emoji" aria-hidden="true">🛒</div>
                  <div>
                    <div class="title"><?php echo isset($poleTextu[23]) ? $poleTextu[23] : 'Nenašel jsem text'; ?></div>
                    <div class="desc"><?php echo isset($poleTextu[24]) ? $poleTextu[24] : 'Nenašel jsem text'; ?></div>
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
                    <?php echo isset($poleTextu[25]) ? $poleTextu[25] : 'Nenašel jsem text'; ?>
                </button>
                <h2><?php echo isset($poleTextu[26]) ? $poleTextu[26] : 'Nenašel jsem text'; ?></h2>
            </header>
            <div class="content">
                <div class="list">
                    <a class="item focusable" href="ubytovani.php#checkin" tabindex="0">
                        <h3><?php echo isset($poleTextu[27]) ? $poleTextu[27] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[28]) ? $poleTextu[28] : 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="ubytovani.php#odpad" tabindex="0">
                        <h3><?php echo isset($poleTextu[29]) ? $poleTextu[29] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[30]) ? $poleTextu[30] : 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="ubytovani.php#kuchyne" tabindex="0">
                        <h3><?php echo isset($poleTextu[31]) ? $poleTextu[31] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[32]) ? $poleTextu[32] : 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="ubytovani.php#bezpecnost" tabindex="0">
                        <h3><?php echo isset($poleTextu[33]) ? $poleTextu[33] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[34]) ? $poleTextu[34] : 'Nenašel jsem text'; ?></p>
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
                    <?php echo isset($poleTextu[47]) ? $poleTextu[47] : 'Nenašel jsem text'; ?>
                </button>
                <h2><?php echo isset($poleTextu[48]) ? $poleTextu[48] : 'Nenašel jsem text'; ?></h2>
            </header>
            <div class="content">
                <div class="list">
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?php echo isset($poleTextu[49]) ? $poleTextu[49] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[50]) ? $poleTextu[50] : 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?php echo isset($poleTextu[51]) ? $poleTextu[51] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[52]) ? $poleTextu[52] : 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?php echo isset($poleTextu[53]) ? $poleTextu[53] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[54]) ? $poleTextu[54] : 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?php echo isset($poleTextu[55]) ? $poleTextu[55] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[56]) ? $poleTextu[56] : 'Nenašel jsem text'; ?></p>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Počasí -->
    <section class="panel" id="panel-pocasi" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Počasí">
        <div class="box" role="document">
            <header class="panelhead">
                <button class="backbtn" data-close><?php echo isset($poleTextu[57]) ? $poleTextu[57] : 'Nenašel jsem text'; ?></button>
                <h2><?php echo isset($poleTextu[58]) ? $poleTextu[58] : 'Nenašel jsem text'; ?></h2>
            </header>
            <div class="content">
                <div class="list">
                    <a class="item focusable" href="pocasi.php?lang=<?= $jazyk_value ?>#dnes" tabindex="0">
                        <h3><?php echo isset($poleTextu[59]) ? $poleTextu[59] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[60]) ? $poleTextu[60] : 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="pocasi.php?lang=<?= $jazyk_value ?>#zitra" tabindex="0">
                        <h3><?php echo isset($poleTextu[61]) ? $poleTextu[61] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[62]) ? $poleTextu[62] : 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="pocasi.php?lang=<?= $jazyk_value ?>#vyhled" tabindex="0">
                        <h3><?php echo isset($poleTextu[63]) ? $poleTextu[63] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[64]) ? $poleTextu[64] : 'Nenašel jsem text'; ?></p>
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
                  <?php echo isset($poleTextu[67]) ? $poleTextu[67] : 'Nenašel jsem text'; ?>
              </button>
              <h2><?php echo isset($poleTextu[68]) ? $poleTextu[68] : 'Nenašel jsem text'; ?></h2>
          </header>
          <div class="content">
              <div class="list">
                  <a class="item focusable" href="lyze.php#lyze" tabindex="0">
                      <h3><?php echo isset($poleTextu[69]) ? $poleTextu[69] : 'Nenašel jsem text'; ?></h3>
                      <p><?php echo isset($poleTextu[70]) ? $poleTextu[70] : 'Nenašel jsem text'; ?></p>
                  </a>
                  <a class="item focusable" href="lyze.php#skibus" tabindex="0">
                      <h3><?php echo isset($poleTextu[71]) ? $poleTextu[71] : 'Nenašel jsem text'; ?></h3>
                      <p><?php echo isset($poleTextu[72]) ? $poleTextu[72] : 'Nenašel jsem text'; ?></p>
                  </a>
                  <a class="item focusable" href="#" tabindex="0">
                      <h3><?php echo isset($poleTextu[73]) ? $poleTextu[73] : 'Nenašel jsem text'; ?></h3>
                      <p><?php echo isset($poleTextu[74]) ? $poleTextu[74] : 'Nenašel jsem text'; ?></p>
                  </a>
                  <a class="item focusable" href="#" tabindex="0">
                      <h3><?php echo isset($poleTextu[75]) ? $poleTextu[75] : 'Nenašel jsem text'; ?></h3>
                      <p><?php echo isset($poleTextu[76]) ? $poleTextu[76] : 'Nenašel jsem text'; ?></p>
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
                    <?php echo isset($poleTextu[77]) ? $poleTextu[77] : 'Nenašel jsem text'; ?>
                </button>
                <h2><?php echo isset($poleTextu[78]) ? $poleTextu[78] : 'Nenašel jsem text'; ?></h2>
            </header>
            <div class="content">
                <div class="list">
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?php echo isset($poleTextu[79]) ? $poleTextu[79] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[80]) ? $poleTextu[80] : 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?php echo isset($poleTextu[81]) ? $poleTextu[81] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[82]) ? $poleTextu[82] : 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?php echo isset($poleTextu[83]) ? $poleTextu[83] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[84]) ? $poleTextu[84] : 'Nenašel jsem text'; ?></p>
                    </a>
                    <a class="item focusable" href="#" tabindex="0">
                        <h3><?php echo isset($poleTextu[85]) ? $poleTextu[85] : 'Nenašel jsem text'; ?></h3>
                        <p><?php echo isset($poleTextu[86]) ? $poleTextu[86] : 'Nenašel jsem text'; ?></p>
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
