<?php
declare(strict_types=1);

$jazyk_value   = (isset($_GET['lang']) && $_GET['lang'] !== '') ? $_GET['lang'] : 'cs';
$stranka_value = "pocasi.php";
include 'texty.php';

/**
 * Indexy textů v DB (už máš nastavené – nechávám podle tvého příkladu)
 */
$T = [
  'weather_title' => 8,   // "Počasí"
  'location_name' => 14,  // "Apolena – Klínovec" / "Apolena"
  'updated'       => 9,   // "aktualizace"
  'tomorrow'      => 15,  // "Zítra"
  'next_days'     => 16,  // "Další dny"
  'wind'          => 12,  // "Vítr"
  'gust'          => 13,  // "Náraz"
  'stale'         => 10,  // "Zobrazuji uloženou předpověď..."
  'err_prefix'    => 11,  // "Chyba počasí:"
];

/**
 * Mapování OWM weather_code (prefix icon: 01,02,03..) -> index v DB
 * DOPLŇ si sem správné indexy pro všechny jazyky už máš v DB přes texty.php
 */
$W = [
  '01' => 17, // jasno / clear / klar
  '02' => 18, // polojasno
  '03' => 19, // oblačno
  '04' => 20, // zataženo
  '09' => 21, // přeháňky
  '10' => 22, // déšť
  '11' => 23, // bouřky
  '13' => 24, // sněžení
  '50' => 25, // mlha
];
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
    <button class="backbtn" id="backBtn" type="button" aria-label="Zpět">
      <?php echo $poleTextu[7] ?? 'Nenašel jsem text'; ?>
    </button>
  </header>

  <main class="manual-main" id="manualMain" aria-label="Obsah manuálu">

    <!-- DNES -->
    <section class="manual-section pocasi" id="dnes" tabindex="-1">
        <h2 class="pocasi-title" id="cityLine"></h2>

            <div class="pocasi-card main">
                <div class="footerline" id="todayDate">—</div>
                <div class="temp" id="todayTemp">—</div>
            </div>

            <div class="pocasi-card detail">
                <div class="wicon" id="todayIcon">🌡️</div>
                <div class="pocasi-detail-text">
                    <div class="meta" id="todayDesc">—</div>
                    <div class="meta" id="todayWind">—</div>
                </div>
            </div>
    </section>

    <!-- ZITRA -->
    <section class="manual-section pocasi" id="zitra" tabindex="-1">
      <h2 class="pocasi-title"><?php echo $poleTextu[$T['tomorrow']] ?? 'Zítra'; ?></h2>

        <div class="pocasi-card main">
            <div class="footerline" id="tomDate">—</div>
            <div class="temp" id="tomTemp">—</div>
        </div>

        <div class="pocasi-card detail">
            <div class="wicon" id="tomIcon">🌡️</div>
            <div class="pocasi-detail-text">
                <div class="meta" id="tomDesc">—</div>
                <div class="meta" id="tomWind">—</div>
            </div>
        </div>
    </section>

    <!-- DALSI DNY -->
    <section class="manual-section" id="vyhled" tabindex="-1">
        <h2 class="pocasi-title"><?php echo $poleTextu[$T['next_days']] ?? 'Další dny'; ?></h2>
        <div class="days" id="longDays"></div>
    </section>

    <!-- STATUS (volitelně) -->
    <div id="status" style="display:none"></div>

  </main>
</div>

<script>
  const LANG = <?php echo json_encode($jazyk_value, JSON_UNESCAPED_UNICODE); ?>;

  // Připravíme celý poleTextu do JS (kvůli mapování weather_code -> index -> text)
  const POLE_TEXTU = <?php echo json_encode($poleTextu, JSON_UNESCAPED_UNICODE); ?>;

  // UI texty (z DB)
  const I18N = {
    weatherTitle: <?php echo json_encode($poleTextu[$T['weather_title']] ?? '???', JSON_UNESCAPED_UNICODE); ?>,
    locationName: <?php echo json_encode($poleTextu[$T['location_name']] ?? '???', JSON_UNESCAPED_UNICODE); ?>,
    updated:      <?php echo json_encode($poleTextu[$T['updated']] ?? '???', JSON_UNESCAPED_UNICODE); ?>,
    windLabel:    <?php echo json_encode($poleTextu[$T['wind']] ?? '???', JSON_UNESCAPED_UNICODE); ?>,
    gustLabel:    <?php echo json_encode($poleTextu[$T['gust']] ?? '???', JSON_UNESCAPED_UNICODE); ?>,
    stale:        <?php echo json_encode($poleTextu[$T['stale']] ?? '???', JSON_UNESCAPED_UNICODE); ?>,
    errPrefix:    <?php echo json_encode($poleTextu[$T['err_prefix']] ?? '???', JSON_UNESCAPED_UNICODE); ?>,
  };

  // Mapování OWM weather_code -> index v DB (podle PHP $W)
  const WEATHER_CODE_TO_TEXTIDX = <?php echo json_encode($W, JSON_UNESCAPED_UNICODE); ?>;

  function weatherTextByCode(code){
    if(!code) return "—";
    const idx = WEATHER_CODE_TO_TEXTIDX[String(code)] ?? null;
    if(idx == null) return "—";
    return POLE_TEXTU[idx] ?? "—";
  }

  function iconToEmoji(icon){
    if(!icon) return "🌡️";
    const p = String(icon).slice(0,2);
    if(p==="01") return "☀️";
    if(p==="02") return "🌤️";
    if(p==="03") return "☁️";
    if(p==="04") return "☁️";
    if(p==="09") return "🌧️";
    if(p==="10") return "🌦️";
    if(p==="11") return "⛈️";
    if(p==="13") return "🌨️";
    if(p==="50") return "🌫️";
    return "🌡️";
  }

    function windDir8(deg, jazyk_value = "cs") {
      if (deg == null || !isFinite(deg)) return "";

      const map = {
        cs: ["S", "SV", "V", "JV", "J", "JZ", "Z", "SZ"],
        de: ["N", "NO", "O", "SO", "S", "SW", "W", "NW"],
        en: ["N", "NE", "E", "SE", "S", "SW", "W", "NW"]
      };

      const dirs = map[jazyk_value] || map.cs;
      return dirs[Math.round(deg / 45) % 8];
    }

function fmtWind(w){
  if(!w || w.speed_kmh == null) return "—";
  const dir = windDir8(w.deg, LANG);   // ✅ tady
  let s = `🌬️ ${I18N.windLabel}: ${w.speed_kmh} km/h${dir ? " • " + dir : ""}`;
  if(w.gust_kmh != null) s += ` • ${I18N.gustLabel}: ${w.gust_kmh} km/h`;
  return s;
}

  function fmtMinMax(min,max){
    if(min==null || max==null) return "—";
    const r = (x)=>Math.round(Number(x));
    return `${r(max)}° / ${r(min)}°`;
  }

  function dayLabel(dateStr){
    const d = new Date(dateStr + "T00:00:00");
    return d.toLocaleDateString(
      LANG === "de" ? "de-DE" : (LANG === "en" ? "en-GB" : "cs-CZ"),
      { weekday:"long", day:"2-digit", month:"2-digit" }
    );
  }

  function showStatus(type, text){
    const el = document.getElementById("status");
    if(!el) return;
    el.style.display = "block";
    el.textContent = text;
  }

  async function run(){
    const url = `pocasi_data.php?lang=${encodeURIComponent(LANG)}`;
    const res = await fetch(url, {cache:"no-store"});
    const data = await res.json();

    if(data.error){
      showStatus("err", `${I18N.errPrefix} ${data.detail || data.error}`);
      return;
    }

    // Nadpis čistě z DB (žádné OWM city)
    if(data._meta){
      const t = new Date((data._meta.fetched_at||0)*1000);
      const timeStr = isFinite(t)
        ? t.toLocaleTimeString("cs-CZ",{hour:"2-digit",minute:"2-digit"})
        : "";
      document.getElementById("cityLine").textContent =
        `${I18N.weatherTitle} – ${I18N.locationName}` + (timeStr ? ` • ${I18N.updated} ${timeStr}` : "");
    }

    if(data._meta && data._meta.stale){
      showStatus("warn", I18N.stale);
    }

    const today = data.today;
    if(today){
      document.getElementById("todayTemp").textContent = fmtMinMax(today.min, today.max);
      document.getElementById("todayDesc").textContent = weatherTextByCode(today.weather_code);
      document.getElementById("todayIcon").textContent = iconToEmoji(today.icon);
      document.getElementById("todayDate").textContent = dayLabel(today.date);
      document.getElementById("todayWind").textContent = fmtWind(today.wind);
    }

    const tom = data.tomorrow;
    if(tom){
      document.getElementById("tomTemp").textContent = fmtMinMax(tom.min, tom.max);
      document.getElementById("tomDesc").textContent = weatherTextByCode(tom.weather_code);
      document.getElementById("tomIcon").textContent = iconToEmoji(tom.icon);
      document.getElementById("tomDate").textContent = dayLabel(tom.date);
      document.getElementById("tomWind").textContent = fmtWind(tom.wind);
    }

    const wrap = document.getElementById("longDays");
    wrap.innerHTML = "";
    (data.longterm || []).forEach(d=>{
      const row = document.createElement("div");
      row.className = "row";
      row.innerHTML = `
        <div class="l">
          <div class="dicon">${iconToEmoji(d.icon)}</div>
          <div style="min-width:0">
            <div class="dname">${dayLabel(d.date)}</div>
            <div class="desc">${weatherTextByCode(d.weather_code)}</div>
          </div>
        </div>
        <div class="minmax">${fmtMinMax(d.min, d.max)}</div>
      `;
      wrap.appendChild(row);
    });
  }

  run().catch(err=>{
    showStatus("err", err.message);
  });
</script>
</body>
</html>
