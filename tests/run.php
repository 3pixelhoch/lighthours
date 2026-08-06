<?php
/**
 * Testlauf ohne Framework.
 *
 *   php tests/run.php
 *
 * Prüft Sonnenstand, Lichtphasen, Zeitzonen und die ICS-Erzeugung.
 * Die erwarteten Sonnenhöhen stammen aus einer unabhängigen Referenz­
 * implementierung (Python/Astral) und sind hier fest hinterlegt.
 */

declare(strict_types=1);

require_once __DIR__ . '/../lib/bootstrap.php';

use LightHours\I18n;
use LightHours\Ics;
use LightHours\LightPhases;
use LightHours\Sun;
use LightHours\Timezone;

$passed = 0;
$failed = 0;

function check(string $name, bool $ok, string $detail = ''): void
{
    global $passed, $failed;
    if ($ok) {
        $passed++;
        echo "  ok    {$name}\n";
    } else {
        $failed++;
        echo "  FEHLT {$name}" . ($detail !== '' ? "  ({$detail})" : '') . "\n";
    }
}

function near(float $a, float $b, float $tolerance): bool
{
    return abs($a - $b) <= $tolerance;
}

echo "\nSonnenstand\n";

// Referenzwerte: Sonnenhöhe (geometrisch) laut Astral
$altCases = [
    ['2026-06-21T12:00:00Z', 53.5511,   9.9937,  59.0373],
    ['2026-12-21T12:00:00Z', 53.5511,   9.9937,  12.4780],
    ['2026-03-20T06:00:00Z', 40.7128, -74.0060, -47.4521],
    ['2026-09-22T22:00:00Z',-33.8688, 151.2093,  26.9123],
];
foreach ($altCases as [$iso, $lat, $lon, $expect]) {
    $got = Sun::altitude(strtotime($iso), $lat, $lon, false);
    check("Sonnenhöhe {$iso}", near($got, $expect, 0.02), sprintf('%.4f statt %.4f', $got, $expect));
}

echo "\nLichtphasen\n";

$tz  = new DateTimeZone('Europe/Berlin');
$day = new DateTimeImmutable('2026-06-21', $tz);
$ph  = LightPhases::forDay($day, 53.5511, 9.9937, null, $tz);

check('vier Phasen im Sommer', count($ph) === 4, count($ph) . ' gefunden');

$byEvent = [];
foreach ($ph as $p) {
    $byEvent[$p['event']] = $p;
}
check('alle Terminarten vorhanden', count(array_diff(LightPhases::EVENTS, array_keys($byEvent))) === 0);
check('Blaue Stunde liegt morgens vor der Goldenen',
    $byEvent['blue_morning']['start'] < $byEvent['golden_morning']['start']);
check('Goldene Stunde liegt abends vor der Blauen',
    $byEvent['golden_evening']['start'] < $byEvent['blue_evening']['start']);
check('Morgenphase geht der Abendphase voraus',
    $byEvent['golden_morning']['start'] < $byEvent['golden_evening']['start']);

foreach ($ph as $p) {
    check("Phase {$p['event']}: Ende nach Beginn", $p['end'] > $p['start']);
}

// Sonnenhöhen an den Phasengrenzen müssen den Definitionen entsprechen
$bounds = [
    'blue_morning'   => [-6.0, -4.0],
    'golden_morning' => [-4.0,  6.0],
    'golden_evening' => [ 6.0, -4.0],
    'blue_evening'   => [-4.0, -6.0],
];
foreach ($bounds as $event => [$from, $to]) {
    $p = $byEvent[$event];
    $a = Sun::altitude($p['start'], 53.5511, 9.9937);
    $b = Sun::altitude($p['end'], 53.5511, 9.9937);
    check("Grenzen von {$event} treffen die Definition",
        near($a, $from, 0.05) && near($b, $to, 0.05),
        sprintf('%.3f/%.3f statt %.1f/%.1f', $a, $b, $from, $to));
}

echo "\nOrtsdatum und Polarregionen\n";

// Termine müssen am angefragten Ortsdatum liegen, auch fernab von Greenwich
foreach ([['Australia/Sydney', -33.8688, 151.2093], ['America/Los_Angeles', 34.0522, -118.2437]] as [$zone, $la, $lo]) {
    $z = new DateTimeZone($zone);
    $d = new DateTimeImmutable('2026-03-20', $z);
    $ok = true;
    foreach (LightPhases::forDay($d, $la, $lo, null, $z) as $p) {
        $local = (new DateTimeImmutable('@' . $p['start']))->setTimezone($z);
        if ($local->format('Y-m-d') !== '2026-03-20') {
            $ok = false;
        }
    }
    check("Termine liegen am Ortsdatum ({$zone})", $ok);
}

// Polarnacht: Spitzbergen im Dezember hat keine Goldene Stunde
$svalbard = new DateTimeZone('Arctic/Longyearbyen');
$polar = LightPhases::forDay(
    new DateTimeImmutable('2026-12-21', $svalbard), 78.22, 15.65, null, $svalbard
);
check('Polarnacht liefert keine erfundenen Termine', count($polar) === 0, count($polar) . ' Termine');

echo "\nAbweichung im Radius\n";

check('größerer Radius bedeutet größere Abweichung',
    LightPhases::maxDeviationMinutes(53.55, 100) > LightPhases::maxDeviationMinutes(53.55, 25));
check('100 km in Hamburg ergeben rund 8 Minuten',
    LightPhases::maxDeviationMinutes(53.55, 100) === 8,
    (string) LightPhases::maxDeviationMinutes(53.55, 100));
check('Abweichung ist nie null', LightPhases::maxDeviationMinutes(0.0, 1) >= 1);

echo "\nZeitzonen\n";

check('Ländercode mit einer Zone ist eindeutig', Timezone::guess(53.55, 9.99, 'DE') === 'Europe/Berlin');
check('Land mit mehreren Zonen wird geografisch aufgelöst',
    Timezone::guess(34.0522, -118.2437, 'US') === 'America/Los_Angeles',
    Timezone::guess(34.0522, -118.2437, 'US'));
check('ohne Ländercode wird die nächste Zone gewählt',
    Timezone::guess(35.6762, 139.6503) === 'Asia/Tokyo', Timezone::guess(35.6762, 139.6503));
// Länder mit mehreren Kennungen, die dieselbe Zeit zeigen: Es muss die geläufige
// Schreibweise gewinnen, nicht die alphabetisch erste oder die nächstgelegene
// Sonderzone. Deutschland führt neben Europe/Berlin auch Europe/Busingen.
$zonen = [
    ['Hamburg',      53.5511,    9.9937, 'DE', 'Europe/Berlin'],
    ['München',      48.1351,   11.5820, 'DE', 'Europe/Berlin'],
    ['Köln',         50.9375,    6.9603, 'DE', 'Europe/Berlin'],
    ['New York',     40.7128,  -74.0060, 'US', 'America/New_York'],
    ['Denver',       39.7392, -104.9903, 'US', 'America/Denver'],
    ['Los Angeles',  34.0522, -118.2437, 'US', 'America/Los_Angeles'],
    ['Honolulu',     21.3069, -157.8583, 'US', 'Pacific/Honolulu'],
    ['Sydney',      -33.8688,  151.2093, 'AU', 'Australia/Sydney'],
    ['Perth',       -31.9523,  115.8613, 'AU', 'Australia/Perth'],
    ['Moskau',       55.7558,   37.6173, 'RU', 'Europe/Moscow'],
    ['São Paulo',   -23.5505,  -46.6333, 'BR', 'America/Sao_Paulo'],
];
foreach ($zonen as [$ort, $la, $lo, $cc, $soll]) {
    $ist = Timezone::guess($la, $lo, $cc);
    check("Zeitzone {$ort}", $ist === $soll, "{$ist} statt {$soll}");
}

// Ohne Ländercode besteht die Kandidatenliste aus allen Zonen der Welt. Unter
// den gleich tickenden gewann früher die kürzeste Kennung – also UTC oder
// Etc/GMT. Für Reykjavík kam so "UTC" heraus: zeitlich richtig, als
// Kalenderbeschriftung falsch.
$ohneLand = [
    ['Reykjavík',   64.1466, -21.9426, 'Atlantic/Reykjavik'],
    ['Accra',        5.6037,  -0.1870, 'Africa/Accra'],
    ['Lissabon',    38.7223,  -9.1393, 'Europe/Lisbon'],
    ['Dublin',      53.3498,  -6.2603, 'Europe/Dublin'],
    ['London',      51.5072,  -0.1276, 'Europe/London'],
];
foreach ($ohneLand as [$ort, $la, $lo, $soll]) {
    $ist = Timezone::guess($la, $lo);
    check("Zeitzone ohne Ländercode: {$ort}", $ist === $soll, "{$ist} statt {$soll}");
}
check('ohne Ländercode nie eine Etc-Kennung',
    !str_starts_with(Timezone::guess(64.1466, -21.9426), 'Etc/'));

check('ungültige Kennung wird erkannt', !Timezone::isValid('Mars/Olympus'));
check('gültige Kennung wird akzeptiert', Timezone::isValid('Europe/Berlin'));

// Auf manchen Webspaces steht serialize_precision auf 17. Dann liefert die
// API 53.55109999999999814690454513765871524810791015625 statt 53.5511.
// bootstrap.php setzt den Wert deshalb selbst.
check('JSON gibt kurze Fließkommazahlen aus',
    json_encode(['lat' => 53.5511]) === '{"lat":53.5511}',
    json_encode(['lat' => 53.5511]));

echo "\nSonnenauf- und -untergang\n";

// Der Bezugspunkt im Termin. Die Goldene Stunde endet abends 25 Minuten nach
// Sonnenuntergang; ohne diese Angabe wirkt der Eintrag falsch.
//
// -0,833 Grad ist eine GEOMETRISCHE Angabe, die Refraktion steckt bereits
// darin. Wird zusätzlich die Refraktion angewandt, liegt das Ergebnis
// zweieinhalb Minuten daneben. Genau dieser Fehler war schon einmal drin.
$bremenTz = new DateTimeZone('Europe/Berlin');
$bremen   = LightPhases::forDay(new DateTimeImmutable('2026-08-04'), 53.059482, 8.8145992, null, $bremenTz);

$untergang = null;
$aufgang   = null;
foreach ($bremen as $p) {
    if ($p['event'] === 'golden_evening') { $untergang = $p['horizon']; }
    if ($p['event'] === 'golden_morning') { $aufgang   = $p['horizon']; }
}

// Unabhängig gerechnet (Python/Astral): 2026-08-04, Bremen 28201
$sollUnter = (new DateTimeImmutable('2026-08-04 21:13:19', $bremenTz))->getTimestamp();
$sollAuf   = (new DateTimeImmutable('2026-08-04 05:47:17', $bremenTz))->getTimestamp();

check('Sonnenuntergang Bremen trifft die Referenz',
    $untergang !== null && abs($untergang - $sollUnter) <= 60,
    $untergang === null ? 'kein Wert' : (new DateTimeImmutable('@' . $untergang))
        ->setTimezone($bremenTz)->format('H:i:s') . ' statt 21:13:19');

check('Sonnenaufgang Bremen trifft die Referenz',
    $aufgang !== null && abs($aufgang - $sollAuf) <= 60,
    $aufgang === null ? 'kein Wert' : (new DateTimeImmutable('@' . $aufgang))
        ->setTimezone($bremenTz)->format('H:i:s') . ' statt 05:47:17');

check('Untergang liegt vor dem Ende der Goldenen Stunde',
    $untergang !== null && $untergang < $bremen[count($bremen) - 1]['end'],
    'sonst stimmt die Zuordnung nicht');

check('jede Phase trägt einen Bezugspunkt',
    array_filter($bremen, static fn(array $p): bool => !array_key_exists('horizon', $p)) === []);

check('Morgenphasen zeigen auf den Aufgang, Abendphasen auf den Untergang',
    (static function () use ($bremen): bool {
        foreach ($bremen as $p) {
            $morgens = str_contains($p['event'], '_morning');
            if (($p['rising'] ?? null) !== $morgens) { return false; }
        }
        return true;
    })());

// Alle sechs Sprachen müssen den Bezugspunkt benennen können
foreach (I18n::available() as $l) {
    $t = new I18n($l);
    check("Bezugspunkt übersetzt: {$l}",
        str_contains($t->t('cal.sunset', ['time' => '21:13']), '21:13')
        && str_contains($t->t('cal.sunrise', ['time' => '05:44']), '05:44')
        && str_contains($t->t('cal.event_description', [
            'event' => 'X', 'name' => 'Y', 'start' => '1', 'end' => '2', 'sun' => 'ZZTOP',
        ]), 'ZZTOP'),
        'Platzhalter {time} oder {sun} fehlt');
}

echo "\nKalenderdatei\n";

$i18n = new I18n('de');
$from = new DateTimeImmutable('2026-06-01', $tz);
$to   = new DateTimeImmutable('2026-06-07', $tz);
$ics  = Ics::build(53.5511, 9.9937, $from, $to, ['golden_morning', 'golden_evening'], $tz, $i18n, 30, 'Hamburg');

check('gültiger Rahmen', str_starts_with($ics, 'BEGIN:VCALENDAR') && str_contains($ics, 'END:VCALENDAR'));
check('14 Termine für 7 Tage', substr_count($ics, 'BEGIN:VEVENT') === 14, (string) substr_count($ics, 'BEGIN:VEVENT'));
check('Zeitzone eingebettet', str_contains($ics, 'BEGIN:VTIMEZONE') && str_contains($ics, 'TZID:Europe/Berlin'));
check('Erinnerungen gesetzt', substr_count($ics, 'BEGIN:VALARM') === 14);
check('Ortsname übernommen', str_contains($ics, 'Hamburg'));
check('Zeilen brechen nach RFC 5545 um', (function () use ($ics): bool {
    foreach (explode("\r\n", $ics) as $line) {
        if (strlen($line) > 75) {
            return false;
        }
    }
    return true;
})());
check('Zeilenenden sind CRLF', !str_contains(str_replace("\r\n", '', $ics), "\n"));

$icsEn = Ics::build(53.5511, 9.9937, $from, $from, ['golden_evening'], $tz, new I18n('en'));
check('englische Bezeichnungen', str_contains($icsEn, 'Golden Hour'));
check('deutsche Bezeichnungen', str_contains($ics, 'Goldene Stunde'));

$noAlarm = Ics::build(53.5511, 9.9937, $from, $from, ['golden_evening'], $tz, $i18n);
check('ohne Erinnerung kein VALARM', !str_contains($noAlarm, 'VALARM'));

$a = Ics::build(53.5511, 9.9937, $from, $from, ['golden_evening'], $tz, $i18n);
$b = Ics::build(53.5511, 9.9937, $from, $from, ['golden_evening'], $tz, $i18n);
$uid = static fn(string $s): array => array_values(array_filter(
    explode("\r\n", $s), static fn(string $l): bool => str_starts_with($l, 'UID')
));
check('UIDs bleiben zwischen Abrufen stabil', $uid($a) === $uid($b));

echo "\nÜbersetzungen\n";

$de = require __DIR__ . '/../lang/de.php';

// Jede Sprache muss denselben Schlüsselsatz führen. Fehlt einer, fällt die
// Oberfläche dort unbemerkt auf Englisch zurück – oder zeigt den Schlüssel.
foreach (I18n::available() as $sprache) {
    $texte  = require __DIR__ . '/../lang/' . $sprache . '.php';
    $fehlt  = array_keys(array_diff_key($de, $texte));
    $zuviel = array_keys(array_diff_key($texte, $de));

    check("Sprache {$sprache}: vollständig", $fehlt === [] && $zuviel === [],
        'fehlt: ' . (implode(', ', array_slice($fehlt, 0, 5)) ?: '–')
        . ' | zuviel: ' . (implode(', ', array_slice($zuviel, 0, 5)) ?: '–'));

    // Platzhalter müssen mitübersetzt worden sein, sonst bleiben Lücken im Text
    $kaputt = [];
    foreach ($de as $schluessel => $text) {
        preg_match_all('/\{(\w+)\}/', $text, $a);
        preg_match_all('/\{(\w+)\}/', (string) ($texte[$schluessel] ?? ''), $b);
        if (array_diff($a[1], $b[1]) !== []) {
            $kaputt[] = $schluessel;
        }
    }
    check("Sprache {$sprache}: Platzhalter erhalten", $kaputt === [],
        implode(', ', array_slice($kaputt, 0, 5)));
}

// Ohne Eigenbezeichnung fällt die Auswahlliste auf das Kürzel zurück –
// „IT“ statt „Italiano“. Sichtbar nur, wenn man genau hinschaut.
foreach (I18n::available() as $sprache) {
    $name = I18n::nativeName($sprache);
    check("Sprache {$sprache}: Eigenbezeichnung vorhanden",
        $name !== strtoupper($sprache) && $name !== '', $name);
}

check('mindestens fünf Sprachen vorhanden', count(I18n::available()) >= 5,
    implode(', ', I18n::available()));
check('Platzhalter werden ersetzt', $i18n->t('cal.name', ['name' => 'Kiel']) === 'lighthours – Kiel');
check('unbekannter Schlüssel fällt auf sich selbst zurück', $i18n->t('gibt.es.nicht') === 'gibt.es.nicht');

echo "\nEinrichtung\n";

check('Platzhalteradresse wird erkannt', !LightHours\user_agent_configured()
    || !str_contains(LH_USER_AGENT, 'example.'),
    'user_agent_configured() muss bei example.org false liefern');
check('Diagnoseseite vorhanden', is_file(__DIR__ . '/../check.php'));
check('Sprachdateien kennen die Einrichtungstexte',
    isset((require __DIR__ . '/../lang/de.php')['setup.title'],
          (require __DIR__ . '/../lang/en.php')['setup.title']));

echo "\nSuchmaschinen\n";

check('Vorschaubild für jede Sprache vorhanden', (function (): bool {
    foreach (I18n::available() as $l) {
        if (!is_file(__DIR__ . '/../assets/img/og-' . $l . '.png')) {
            return false;
        }
    }
    return true;
})(), 'assets/img/og-<sprache>.png');

check('robots.php vorhanden und nennt die Sitemap',
    is_file(__DIR__ . '/../robots.php')
    && str_contains((string) file_get_contents(__DIR__ . '/../robots.php'), 'Sitemap:'));

check('Kalender und Schnittstellen aus dem Index gehalten', (function (): bool {
    $r = (string) file_get_contents(__DIR__ . '/../robots.php');
    foreach (['/calendar.php', '/api/', '/check.php'] as $pfad) {
        if (!str_contains($r, 'Disallow: ' . $pfad)) {
            return false;
        }
    }
    return true;
})());

// Im Repository ist die Basis-URL absichtlich leer – dort gibt es keine.
// Auf einer Installation muss sie stehen, sonst raten die kanonischen Adressen.
check('Basis-URL ist definiert', defined('LH_BASE_URL'));
if (LH_BASE_URL === '') {
    echo "  hinweis  LH_BASE_URL ist leer – vor dem Onlinestellen eintragen\n";
}
check('Versionsnummer gesetzt', defined('LH_VERSION') && LH_VERSION !== '');

// Eine fest eingetragene Domain im Code landet in jedem Fork und in jedem
// Suchindex. Sie gehört ausschließlich in die Konfiguration.
$fest = [];
foreach (array_merge(glob(__DIR__ . '/../lib/*.php') ?: [], glob(__DIR__ . '/../lang/*.php') ?: [],
                     glob(__DIR__ . '/../partials/*.php') ?: [], glob(__DIR__ . '/../api/*.php') ?: [],
                     [__DIR__ . '/../robots.php', __DIR__ . '/../sitemap.php']) as $datei) {
    $inhalt = (string) file_get_contents($datei);
    if (basename($datei) === 'config.php') {
        continue;   // dort gehört sie hin
    }
    if (preg_match('/lighthours\.app|schuchaert|3pixelhoch/i', $inhalt)) {
        $fest[] = basename($datei);
    }
}
check('keine festen Domains oder Namen außerhalb der Konfiguration',
    $fest === [], implode(', ', $fest));

echo "\nZählung aktiver Kalender\n";

// Eine zwischengespeicherte Null hätte die Zahl eine Stunde lang blockiert –
// genau nach einem Upload, bei dem der Zwischenspeicher geleert wurde.
$stats = (string) file_get_contents(__DIR__ . '/../lib/Stats.php');
check('gespeicherte Null wird verworfen', str_contains($stats, '$wert > 0'),
    'sonst gilt eine Null eine Stunde lang');
check('Null wird gar nicht erst gespeichert', str_contains($stats, 'if ($summe > 0)'));

// Der Zähler muss aus mehreren Tagesdateien zusammenrechnen
$probe = LH_CACHE_DIR . '/stats';
@mkdir($probe, 0775, true);
$gestern = gmdate('Y-m-d', time() - 86400);
$heute   = gmdate('Y-m-d');
@file_put_contents($probe . '/' . $gestern . '.txt', "aaaaaaaaaaaaaaaa\n");
@file_put_contents($probe . '/' . $heute . '.txt', "bbbbbbbbbbbbbbbb\ncccccccccccccccc\n");
@unlink($probe . '/summe.json');

// Statische Zwischenspeicher der Klasse umgehen, indem frisch gezählt wird
$gezaehlt = LightHours\Stats::activeCalendars();
check('zählt über mehrere Tage hinweg', $gezaehlt >= 3, (string) $gezaehlt);

@unlink($probe . '/' . $gestern . '.txt');
@unlink($probe . '/summe.json');

echo "\nVerzeichnisschutz\n";

// Die zentrale .htaccess allein genügt nicht: Manche Hoster laden mod_alias
// nicht, dann greift RedirectMatch ins Leere. Jedes Codeverzeichnis trägt
// deshalb eine eigene Sperre.
foreach (['lib', 'lang', 'legal', 'partials'] as $ordner) {
    $datei = __DIR__ . '/../' . $ordner . '/.htaccess';
    $inhalt = is_file($datei) ? (string) file_get_contents($datei) : '';
    check("Verzeichnis {$ordner} ist gesperrt",
        str_contains($inhalt, 'Require all denied') && str_contains($inhalt, 'Deny from all'),
        'beide Apache-Fassungen nötig');
}

check('Sperre bricht nicht bei fehlendem Modul', (function (): bool {
    foreach (['lib', 'lang', 'legal', 'partials'] as $ordner) {
        $inhalt = (string) @file_get_contents(__DIR__ . '/../' . $ordner . '/.htaccess');
        if (substr_count($inhalt, '<IfModule') !== 2) {
            return false;   // ohne IfModule antwortet Apache mit Fehler 500
        }
    }
    return true;
})());

check('Zwischenspeicher schützt sich beim Anlegen selbst',
    function_exists('LightHours\\verzeichnis_schuetzen'));

echo "\nDesign-Tokens\n";

$css = (string) file_get_contents(__DIR__ . '/../assets/css/tokens.css');

// Die Dunkelmodus-Werte stehen zweimal (Systemvorgabe und manuelle Wahl).
// Beide Blöcke müssen zeichengleich sein, sonst weicht ein Modus ab.
preg_match("/:root:not\\(\\[data-theme='light'\\]\\) \\{(.+?)\\n  \\}/s", $css, $m1);
preg_match("/:root\\[data-theme='dark'\\] \\{(.+?)\\n\\}/s", $css, $m2);

$norm = static fn(string $b): string => preg_replace('/\s+/', ' ', trim($b));

check('beide Dunkelmodus-Blöcke gefunden', isset($m1[1], $m2[1]));
check('Dunkelmodus-Blöcke sind identisch',
    isset($m1[1], $m2[1]) && $norm($m1[1]) === $norm($m2[1]),
    'Systemvorgabe und manuelle Wahl weichen voneinander ab');

// Jedes semantische Token braucht auch im Dunkelmodus einen Wert
$semantic = ['--bg','--fg','--fg-muted','--accent','--accent-weak','--border','--card','--focus','--on-accent'];
$missing = array_values(array_filter($semantic,
    static fn(string $t): bool => !str_contains($m2[1] ?? '', $t . ':')));
check('alle semantischen Tokens im Dunkelmodus gesetzt', $missing === [], implode(', ', $missing));

check('Schriftfarbe auf Gold nie fest verdrahtet',
    !str_contains((string) file_get_contents(__DIR__ . '/../assets/css/style.css'), 'color: #fff'));

// Eine Regel, die nur auf prefers-color-scheme hört, greift bei manuell
// gewähltem Modus nicht – der häufigste stille Fehler im Dunkelmodus.
$style = (string) file_get_contents(__DIR__ . '/../assets/css/style.css');
preg_match_all('/@media \(prefers-color-scheme: dark\)\s*\{(.*?)\n\}/s', $style, $bloecke);
$ohneGegenstueck = [];
foreach ($bloecke[1] ?? [] as $block) {
    preg_match_all('/\.([\w-]+)/', $block, $klassen);
    foreach (array_unique($klassen[1] ?? []) as $klasse) {
        if (!str_contains($style, "data-theme='dark'] .{$klasse}")
            && !str_contains($block, "data-theme='light'")) {
            $ohneGegenstueck[] = $klasse;
        }
    }
}
check('keine Dunkelmodus-Regel ohne manuelles Gegenstück',
    $ohneGegenstueck === [], implode(', ', array_unique($ohneGegenstueck)));

// Beim Umschreiben eines CSS-Blocks können Regeln unbemerkt verlorengehen.
// Jede Klasse, die im Markup steht, braucht eine Entsprechung im Stylesheet.
$markup = '';
foreach (['index.php', 'check.php', 'partials/footer.php', 'partials/rechtstext.php'] as $datei) {
    $markup .= (string) file_get_contents(__DIR__ . '/../' . $datei);
}
$gesamtCss = $style . (string) file_get_contents(__DIR__ . '/../assets/css/tokens.css');

preg_match_all('/class="([^"<]+)"/', $markup, $treffer);
$imMarkup = [];
foreach ($treffer[1] as $liste) {
    foreach (preg_split('/\s+/', trim($liste)) as $klasse) {
        if ($klasse !== '' && !str_contains($klasse, '<')) {
            $imMarkup[$klasse] = true;
        }
    }
}
preg_match_all('/\.([a-zA-Z][\w-]*)/', $gesamtCss, $imCss);
$gestaltet = array_flip($imCss[1]);

$ohneRegel = array_values(array_filter(
    array_keys($imMarkup),
    static fn(string $k): bool => !isset($gestaltet[$k]) && !str_starts_with($k, 'maplibre')
));
check('jede Klasse im Markup hat eine CSS-Regel', $ohneRegel === [],
    implode(', ', array_slice($ohneRegel, 0, 6)));

// Regeln, ohne die die Seite auf bestimmten Geräten auseinanderfällt.
// Sie sind mir dreimal beim Umschreiben benachbarter Blöcke verlorengegangen,
// ohne dass irgendetwas fehlschlug – deshalb diese Prüfung.
// Gegen Kommentare prüfen wäre wertlos – die zählen nicht als Regel.
$nurRegeln = preg_replace('#/\\*.*?\\*/#s', '', $style);

$unverzichtbar = [
    'Marke ist auf schmalen Geräten geregelt'  => '.brand-name { font-size',
    'Sprachwahl ist eine Auswahlliste'         => '.lang-menu summary {',
    'Bildschirmfotos folgen dem Farbmodus'     => '.screen-dark',
    'Farbmodus-Umschalter ist gestaltet'       => '.theme-toggle {',
];
foreach ($unverzichtbar as $was => $spur) {
    check("Regel vorhanden: {$was}", str_contains($nurRegeln, $spur), $spur);
}

check('mobile Kopfzeile blendet den Sprachnamen aus', (function (string $style): bool {
    if (!preg_match('/@media \(max-width: 640px\) \{(.*?)\n\}\n/s', $style, $treffer)) {
        return false;
    }
    foreach (['.site-header .wrap', '.brand', '.lang-full', '.lang-current'] as $teil) {
        if (!str_contains($treffer[1], $teil)) {
            return false;
        }
    }
    return true;
})($style));

// Von den beiden Bildfassungen darf immer nur eine sichtbar sein. Der Fehler
// lag nicht in der Logik, sondern im Gewicht: `.screen-frame img` mit seinem
// display schlug ein knappes `.screen-dark`. Sichtbar wurde das nur im
// Systemmodus bei hellem System – also genau dort, wo man selten hinschaut.
$ohneKommentare = preg_replace('#/\*.*?\*/#s', '', $style);

$gewicht = static function (string $sel): int {
    preg_match_all('/\.[\w-]+|\[[^\]]+\]|:(?!:)[\w-]+/', $sel, $klassen);
    preg_match_all('/(?:^|\s|>)([a-z]+)(?![\w-]*[\(\[])/', $sel, $elemente);
    return count($klassen[0]) * 100 + count($elemente[0]);
};

$bildRegel = $gewicht('.screen-frame img');
$grundHell = $gewicht('.screen-frame .screen-light');
$grundDunkel = $gewicht('.screen-frame .screen-dark');

check('Bildumschaltung wiegt schwerer als die allgemeine Bildregel',
    $grundHell > $bildRegel && $grundDunkel > $bildRegel,
    "img={$bildRegel} hell={$grundHell} dunkel={$grundDunkel}");

check('allgemeine Bildregel setzt kein display',
    (bool) preg_match('/\.screen-frame img \{[^}]*\}/', $ohneKommentare, $t)
    && !str_contains($t[0], 'display'),
    'sonst überstimmt sie die Umschaltung');

check('beide Bildfassungen werden für jeden Modus geregelt', (function (string $css): bool {
    foreach (["[data-theme='dark']", "[data-theme='light']", ":not([data-theme='light'])"] as $modus) {
        foreach (['screen-light', 'screen-dark'] as $fassung) {
            // Gegen wechselnde Ausrichtung unempfindlich prüfen
            $muster = '/' . preg_quote($modus, '/') . '\\s+\\.screen-frame\\s+\\.' . $fassung . '\\b/';
            if (!preg_match($muster, $css)) {
                return false;
            }
        }
    }
    return true;
})($ohneKommentare));

// Die Kopfzeile stand dreimal fast gleich in den Seiten und lief auseinander.
$kopfzeilen = 0;
foreach (['index.php', 'check.php', 'partials/rechtstext.php'] as $datei) {
    $inhalt = (string) file_get_contents(__DIR__ . '/../' . $datei);
    if (str_contains($inhalt, '<header class="site-header">')) {
        $kopfzeilen++;
    }
}
check('Kopfzeile steht nur an einer Stelle', $kopfzeilen === 0,
    "{$kopfzeilen} Seiten bringen eine eigene mit");
check('gemeinsame Kopfzeile vorhanden', is_file(__DIR__ . '/../partials/kopfzeile.php'));

// Die Sprungmarke ist beim Zusammenlegen der Kopfzeilen schon einmal
// verlorengegangen. Sie ist das erste fokussierbare Element der Seite.
$kopfzeile = (string) file_get_contents(__DIR__ . '/../partials/kopfzeile.php');
check('Sprungmarke vorhanden', str_contains($kopfzeile, 'class="skip-link"'));
check('Sprungmarke zielt je Seite anders', str_contains($kopfzeile, 'match ($seiteName)'),
    'sonst zeigt sie auf einen Anker, den es nicht gibt');
check('Sprungmarke steht vor der Kopfzeile',
    strpos($kopfzeile, 'skip-link') < strpos($kopfzeile, '<header'),
    'sonst lässt sie sich nicht als Erstes anspringen');

// Das Logo führte im Impressum auf das Impressum: Es benutzte dieselbe
// Adressfunktion wie die Sprachwahl, die bewusst auf der Seite bleibt.
check('Marke führt zur Startseite',
    preg_match('~class="brand" href="<\?= LightHours\\\\h\(\x27\./\?lang=\x27~', $kopfzeile) === 1,
    'sonst zeigt das Logo auf die Seite, auf der man schon steht');
check('Marke benutzt nicht die Sprachadresse',
    !preg_match('/class="brand" href="[^"]*sprachAdresse/', $kopfzeile));

// Das Diagramm stand zuerst nur auf Deutsch in der Seite. Beim Übersetzen ist
// dreimal ein Backslash aus dem Python-Skript im PHP gelandet, und der Rest der
// Datei blieb dabei syntaktisch gültig - nur die Ausgabe war kaputt.
$diagramm = (string) file_get_contents(__DIR__ . '/../partials/lichtdiagramm.php');
$dMarkup  = substr($diagramm, (int) strpos($diagramm, '?>'));

check('Diagramm enthält keinen fest verdrahteten Text',
    !preg_match('~>(Goldene Stunde|Blaue Stunde|Sonnenuntergang|Horizont)<~', $dMarkup),
    'gehört in die Sprachdateien');
check('Diagramm hat keine entwichenen Variablen',
    !str_contains($diagramm, '\\$i18n'),
    'ein Backslash vor $i18n gibt den Namen als Text aus');

foreach (I18n::available() as $l) {
    $t = new I18n($l);
    $fehlt = [];
    foreach (['heading', 'svg_title', 'svg_desc', 'horizon', 'sunset',
              'legend_golden', 'legend_after', 'legend_blue', 'text_1', 'text_2'] as $k) {
        $wert = $t->t('chart.' . $k);
        if ($wert === '' || $wert === 'chart.' . $k) { $fehlt[] = $k; }
    }
    check("Diagrammtexte vollständig: {$l}", $fehlt === [], implode(', ', $fehlt));
}

check('keine festen Farbwerte im Stylesheet',
    preg_match('/#[0-9A-Fa-f]{6}/', $style) === 0,
    'Farben gehören in tokens.css');

check('color-scheme wird mitgeführt',
    str_contains($css, 'color-scheme: light dark') && str_contains($css, 'color-scheme: dark'));

echo "\n" . str_repeat('─', 46) . "\n";
printf("  %d bestanden, %d fehlgeschlagen\n\n", $passed, $failed);

exit($failed === 0 ? 0 : 1);
