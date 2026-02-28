<?php
// bezpečnost: partial se má volat jen přes include
if (!isset($bezkyDetail) || !is_array($bezkyDetail)) {
    http_response_code(500);
    echo "Detail trasy není k dispozici.";
    exit;
}
?>
<!doctype html>
<html>
<head>
    <?php include __DIR__ . '/../../hlava.php'; ?>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    /* jen pro detail */
    body { margin:0; background-color:#0b1f1a; color: #e6f2ee;}
    .detail-wrap { height: 90vh; overflow: auto; padding: 20px; box-sizing: border-box; }
    #bezkyMap { height: 50vh; max-height: 450px; border-radius: 16px; overflow: hidden;}
    .detail-main{overflow: visible;}
    .detail-section{min-height: 0;   /* globální manual-section má min-height:100% */}
    /* detail page – schovat globální hlavičku a případné fixní prvky */
    .bezky-detail-page header,
    .bezky-detail-page .topbar,
    .bezky-detail-page .header,
    .bezky-detail-page .uvod,
    .bezky-detail-page .nav,
    .bezky-detail-page .menu {display: none !important;}

    /* když globál dává např. padding-top kvůli fixed headeru */
    .bezky-detail-page body,
    .bezky-detail-page .safe,
    .bezky-detail-page .manual-main,
    .bezky-detail-page main {padding-top: 0 !important;margin-top: 0 !important;}
    .bezky-detailBtn {padding:6px 7px; border-radius:14px; margin: 10px}
    
    /* Bezky detail – vypnout focus rámeček na sekci */
    .bezky-detail-page .detail-section:focus,
    .bezky-detail-page .detail-section:focus-visible {outline: none !important; box-shadow: none !important;}
    
    
    .bezky-detail-popis {padding: 10px 0 0 10px;}
</style>

</head>

<body class="bezky-detail-page">
    <div class="detail-wrap">
        <main class="detail-main" aria-label="Detail trasy">
            <section class="detail-section lyze" id="bezky-detail" tabindex="-1">
                <h2 class="lyze-title">📍 <?php echo h($bezkyDetail['nazev']); ?></h2>

                <div class="lyze-card" aria-label="Mapa trasy">
                    <?php if (empty($gpxUrl)): ?>
                        <div class="lyze-row">
                            <span class="lyze-icon">⚠️</span>
                            <span class="lyze-label">Trasa nemá přiřazený GPX soubor.</span>
                        </div>
                    <?php else: ?>
                        <div id="bezkyMap"></div>

                        <div class="bezkyBtns">
                            <button class="bezky-detailBtn" id="zoomIn" type="button">➕ Zoom</button>
                            <button class="bezky-detailBtn" id="zoomOut" type="button">➖ Zoom</button>
                            <button class="bezky-detailBtn" id="recenter" type="button">🎯 Centrovat</button>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="bezky-detail-popis">
                    <?php echo h($bezkyDetail['popis_trasy']); ?>
                </div>

            </section>
        </main>
    </div>

    <?php if (!empty($gpxUrl)): ?>
<script>
(async function () {
  const gpxUrl = <?php echo json_encode($gpxUrl, JSON_UNESCAPED_SLASHES); ?>;

  const map = L.map('bezkyMap', {
    zoomControl: false,
    attributionControl: true,
    dragging: false,
    scrollWheelZoom: false,
    doubleClickZoom: false,
    boxZoom: false,
    keyboard: false,
    tap: false,
    touchZoom: false
  });

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  const res = await fetch(gpxUrl, { cache: 'no-store' });
  if (!res.ok) { alert('Nelze načíst GPX: ' + gpxUrl); return; }

  const text = await res.text();
  const xml  = new DOMParser().parseFromString(text, "application/xml");

  const pts = Array.from(xml.getElementsByTagName("trkpt")).map(p => ([
    parseFloat(p.getAttribute("lat")),
    parseFloat(p.getAttribute("lon"))
  ])).filter(a => Number.isFinite(a[0]) && Number.isFinite(a[1]));

  if (pts.length < 2) { alert('GPX neobsahuje dost bodů.'); return; }

  const line   = L.polyline(pts, { weight: 5 }).addTo(map);
  const bounds = line.getBounds();

  // větší okolí:
  map.fitBounds(bounds.pad(0.25), { padding: [20, 20] });

  L.marker(pts[0]).addTo(map).bindPopup("Start");
  L.marker(pts[pts.length - 1]).addTo(map).bindPopup("Cíl");

  document.getElementById('zoomIn').addEventListener('click', () => map.zoomIn());
  document.getElementById('zoomOut').addEventListener('click', () => map.zoomOut());
  document.getElementById('recenter').addEventListener('click', () => map.fitBounds(bounds.pad(0.25), { padding: [20, 20] }));
})();
</script>
<?php endif; ?>

<script>
(function () {
  const params = new URLSearchParams(location.search);
  const lang = params.get('lang') || 'cs';

  // návrat musí zachovat jazyk
  const listUrl = 'lyze.php?lang=' + encodeURIComponent(lang) + '#bezky';
  const detailUrl = location.href;

  if (window.__bezkyHistoryFixed) return;
  window.__bezkyHistoryFixed = true;

  try {
    history.replaceState({ apolena: 'bezky_list' }, '', listUrl);
  } catch(e) {}

  try {
    history.pushState({ apolena: 'bezky_detail' }, '', detailUrl);
  } catch(e) {}

  const back = document.getElementById('bezkyBack');
  if (back) {
    back.addEventListener('click', (e) => {
      e.preventDefault();
      history.back();
    });
  }
})();
</script>
</body>
</html>
