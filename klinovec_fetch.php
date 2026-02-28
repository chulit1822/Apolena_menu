<?php
declare(strict_types=1);

function fetch_url(string $url, int $timeout = 8): string {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_CONNECTTIMEOUT => $timeout,
        CURLOPT_USERAGENT => 'ApolenaManual/1.0 (+https://apolena.cz)',
        CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml',
            'Accept-Language: cs,en;q=0.8,de;q=0.7',
        ],
    ]);
    $html = curl_exec($ch);
    $err  = curl_error($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($html === false || $code >= 400) {
        throw new RuntimeException("Fetch failed (HTTP $code): $err");
    }
    return (string)$html;
}

// ****************************************************** //
// funce, která řeší cache hodnot ze stránek a v přípdě,  //
// kdy je stránka nedostupná použije starou cache         //
// ****************************************************** //
function cached_fetch(string $cacheFile, int $ttlSeconds, callable $fetcher, ?int $maxStaleSeconds = null): string
{
    $hasCache = is_file($cacheFile);
    $cacheAge = $hasCache ? (time() - filemtime($cacheFile)) : PHP_INT_MAX;

    // 1) Čerstvá cache → vrať ji
    if ($hasCache && $cacheAge >= 0 && $cacheAge <= $ttlSeconds) {
        return (string)file_get_contents($cacheFile);
    }

    // 2) Zkus stáhnout nová data
    try {
        $data = (string)$fetcher();

        // neukládej prázdnou odpověď
        if (trim($data) === '') {
            throw new RuntimeException('Empty response');
        }

        // atomický zápis
        $tmp = $cacheFile . '.tmp';
        @file_put_contents($tmp, $data, LOCK_EX);
        @rename($tmp, $cacheFile);

        return $data;

    } catch (Throwable $e) {

        // 3) Fetch selhal → vrať starou cache (i když je prošlá)
        if ($hasCache) {
            if ($maxStaleSeconds === null || $cacheAge <= $maxStaleSeconds) {
                return (string)file_get_contents($cacheFile);
            }
        }

        // 4) Cache neexistuje nebo je moc stará → chyba
        throw $e;
    }
}


// ****************************************************** //
// funce, která parsuje WEB klínovce a tahá z něj hodnoty //
// ****************************************************** //

function parse_klinovec_aktualne(string $html): array {

    // 1) HTML -> čistý text (důležité!)
    $text = strip_tags($html);
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = preg_replace('/\s+/u', ' ', $text);
    $text = trim($text);

    $out = [
        'updated' => null,
        'snow_cm' => null,
        'snow_type' => null,
        'status_hours' => null,
        'night_ski' => null,
        'lifts_open' => null,
        'slopes_open' => null,
        'km_open' => null,
        'temp_c' => null,
        'temp_place' => null,
    ];

    // Aktualizováno: 19. ledna 2026, 09:26
    if (preg_match('/Aktualizováno:\s*([^|]+?)(?:Sníh:|$)/ui', $text, $m)) {
        $out['updated'] = trim($m[1]);
    }

    // Sníh: 80-100 cm
    if (preg_match('/Sníh:\s*([^|]+)\|/ui', $text, $m)) {
        $out['snow_cm'] = trim($m[1]);
    }

    // Typ sněhu: technický
    if (preg_match('/Typ sněhu:\s*([^|]+)\|/ui', $text, $m)) {
        $out['snow_type'] = trim($m[1]);
    }

    // Stav: 09:00-16:00
    if (preg_match('/Stav:\s*([^|]+)(?:\||$)/ui', $text, $m)) {
        $out['status_hours'] = trim($m[1]);
    }

    // Večerní lyžování: 18:00 - 21:00 - ST, PÁ, SO
    if (preg_match('/Večerní lyžování:\s*([^|]+)\|/ui', $text, $m)) {
        $out['night_ski'] = trim($m[1]);
    }

    // 12 Otevřených lanovek a vleků
    if (preg_match('/(\d+)\s*Otevřených\s*lanovek\s*a\s*vleků/ui', $text, $m)) {
        $out['lifts_open'] = (int)$m[1];
    }

    // 14 Otevřených sjezdovek
    if (preg_match('/(\d+)\s*Otevřených\s*sjezdovek/ui', $text, $m)) {
        $out['slopes_open'] = (int)$m[1];
    }

    // 18km Otevřených km sjezdovek (varianta s mezerou i bez)
    if (preg_match('/(\d+(?:[.,]\d+)?)\s*km\s*Otevřených\s*km\s*sjezdovek/ui', $text, $m)
        || preg_match('/(\d+(?:[.,]\d+)?)km\s*Otevřených\s*km\s*sjezdovek/ui', $text, $m)) {
        $out['km_open'] = str_replace(',', '.', $m[1]) . ' km';
    }

    // -6.0 Horní stanice CineStar Express
    if (preg_match('/([+-]?\d+(?:[.,]\d+)?)\s*(Horní stanice\s+[^()]+)\s*\(všechny teploty\)/ui', $text, $m)) {
        $out['temp_c'] = (float)str_replace(',', '.', $m[1]);
        $out['temp_place'] = trim($m[2]);
    }

    return $out;
}




function get_klinovec_aktualne(int $cacheMinutes = 10): array {
    $url = 'https://klinovec.cz/aktualne-z-klinovce/';
    $cacheFile = __DIR__ . '/objekty/cache/klinovec_aktualne.html';

    if (!is_dir(dirname($cacheFile))) {
        @mkdir(dirname($cacheFile), 0775, true);
    }

    $html = cached_fetch($cacheFile, $cacheMinutes * 60, function() use ($url) {
        return fetch_url($url);
    }, 7 * 24 * 60 * 60);

    return parse_klinovec_aktualne($html);
}

function parse_klinovec_teploty(string $html): array
{
    $text = strip_tags($html);
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = preg_replace('/\s+/u', ' ', $text);
    $text = trim($text);

    $out = [
        'top_cinestar' => null,    // Horní stanice CineStar Express
        'mid' => null,             // Střed kopce
        'bottom_cinestar' => null, // Dolní stanice CineStar Express
        'updated' => null,         // volitelně: aktualizováno (první nalezené)
    ];

    // pomocná funkce: najdi teplotu před názvem stanice
    $findTempFor = function(string $labelPattern) use ($text): ?float {
        // např. "-7.1 °C Horní stanice CineStar Express"
        $re = '/([+-]?\d+(?:[.,]\d+)?)\s*°?\s*C\s*' . $labelPattern . '/ui';
        if (preg_match($re, $text, $m)) {
            return (float)str_replace(',', '.', $m[1]);
        }
        // fallback, kdyby nebylo "°C" (občas se stane)
        $re2 = '/([+-]?\d+(?:[.,]\d+)?)\s*' . $labelPattern . '/ui';
        if (preg_match($re2, $text, $m)) {
            return (float)str_replace(',', '.', $m[1]);
        }
        return null;
    };

    // cílové stanice
    $out['top_cinestar']    = $findTempFor('Horní\s*stanice\s*CineStar\s*Express');
    $out['mid']             = $findTempFor('Střed\s*kopce');
    $out['bottom_cinestar'] = $findTempFor('Dolní\s*stanice\s*CineStar\s*Express');

    // volitelně vezmi první "aktualizováno: 2026-01-19 14:45:05"
    if (preg_match('/aktualizováno:\s*([0-9]{4}-[0-9]{2}-[0-9]{2}\s+[0-9]{2}:[0-9]{2}:[0-9]{2})/ui', $text, $m)) {
        $out['updated'] = trim($m[1]);
    }

    return $out;
}

function get_klinovec_teploty(int $cacheMinutes = 10): array
{
    $url = 'https://klinovec.cz/teplota/';
    $cacheFile = __DIR__ . '/objekty/cache/klinovec_teplota.html';

    if (!is_dir(dirname($cacheFile))) {
        @mkdir(dirname($cacheFile), 0775, true);
    }

    $html = cached_fetch($cacheFile, $cacheMinutes * 60, function() use ($url) {
        return fetch_url($url);
    }, 7 * 24 * 60 * 60);

    return parse_klinovec_teploty($html);
}
