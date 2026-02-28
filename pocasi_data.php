<?php
declare(strict_types=1);

/**
 * pocasi_data.php
 * - ČISTÉ DATOVÉ API
 * - Bez textů, bez DB
 * - Cache 10 minut
 */

ini_set('display_errors', '0');
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

$config = require __DIR__ . '/objekty/weather_config.php';

$lang = isset($_GET['lang']) ? strtolower((string)$_GET['lang']) : 'cs';
$lat   = (float)($config['lat'] ?? 50.4182608);
$lon   = (float)($config['lon'] ?? 12.9965269);
$units = (string)($config['units'] ?? 'metric');

$cacheDir = $config['cache_dir'] ?? (__DIR__ . '/objekty/cache');
$ttl      = (int)($config['cache_ttl_seconds'] ?? 600);

if (!is_dir($cacheDir)) @mkdir($cacheDir, 0775, true);

function readJson(string $f){ return is_file($f) ? json_decode(file_get_contents($f), true) : null; }
function writeJson(string $f, array $d){ file_put_contents($f, json_encode($d, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES), LOCK_EX); }

$cacheKey  = sprintf('owm_%0.4f_%0.4f_%s_%s', $lat, $lon, $units, $lang);
$cacheFile = $cacheDir.'/'.md5($cacheKey).'.json';

if ($c = readJson($cacheFile)) {
  if (time() - ($c['_meta']['fetched_at'] ?? 0) < $ttl) {
    echo json_encode($c, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    exit;
  }
}

$apiKey = trim((string)($config['owm_api_key'] ?? ''));
if ($apiKey === '') {
  http_response_code(500);
  echo json_encode(['error'=>'Missing API key']);
  exit;
}

$url = sprintf(
  'https://api.openweathermap.org/data/2.5/forecast?lat=%s&lon=%s&units=%s&appid=%s',
  $lat, $lon, $units, $apiKey
);

$data = json_decode(file_get_contents($url), true);
if (!isset($data['list'])) {
  http_response_code(502);
  echo json_encode(['error'=>'OWM error']);
  exit;
}

$tz = (int)($data['city']['timezone'] ?? 0);
$days = [];

foreach ($data['list'] as $it) {
  $d = gmdate('Y-m-d', $it['dt'] + $tz);
  $days[$d][] = $it;
}

$out = [];
foreach ($days as $date=>$items) {
  $min=$max=null; $mid=null; $best=99;

  foreach ($items as $i) {
    $min = $min===null ? $i['main']['temp_min'] : min($min,$i['main']['temp_min']);
    $max = $max===null ? $i['main']['temp_max'] : max($max,$i['main']['temp_max']);
    $h = abs((int)gmdate('G',$i['dt']+$tz)-12);
    if ($h < $best){ $best=$h; $mid=$i; }
  }

  $icon = $mid['weather'][0]['icon'] ?? null;

  $out[] = [
    'date'=>$date,
    'min'=>round($min,1),
    'max'=>round($max,1),
    'icon'=>$icon,
    'weather_code'=>substr((string)$icon,0,2),   // <-- KLÍČOVÉ
    'wind'=>[
      'speed_kmh'=>isset($mid['wind']['speed']) ? round($mid['wind']['speed']*3.6) : null,
      'deg'=>$mid['wind']['deg'] ?? null,
      'gust_kmh'=>isset($mid['wind']['gust']) ? round($mid['wind']['gust']*3.6) : null,
    ]
  ];
}

$payload = [
  '_meta'=>[
    'fetched_at'=>time(),
    'lat'=>$lat,
    'lon'=>$lon,
    'nearest_city'=>$data['city']['name'] ?? '',
  ],
  'today'=>$out[0] ?? null,
  'tomorrow'=>$out[1] ?? null,
  'longterm'=>array_slice($out,2,3),
];

writeJson($cacheFile,$payload);
echo json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
