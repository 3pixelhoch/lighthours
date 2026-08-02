<?php
/**
 * Kalenderausgabe (ICS).
 *
 * Diese URL wird von der Kalender-App des Nutzers regelmäßig abgerufen –
 * sie muss also dauerhaft erreichbar bleiben und darf nie eine HTML-Fehlerseite
 * ausliefern.
 *
 * Beispiel:
 *   /calendar.php?lat=53.5511&lon=9.9937&months=24&rolling=1&events=golden_evening
 */

declare(strict_types=1);

require_once __DIR__ . '/lib/bootstrap.php';

use LightHours\I18n;
use LightHours\Ics;
use LightHours\Stats;
use LightHours\Timezone;

[$lat, $lon] = LightHours\read_coords();

$events = LightHours\read_events($_GET['events'] ?? null);
$lang   = (string) ($_GET['lang'] ?? LH_DEFAULT_LANG);
$name   = trim((string) ($_GET['name'] ?? ''));
$name   = mb_substr($name, 0, 120);

// Zeitzone: übergeben oder aus der Länge geschätzt
$tzId = (string) ($_GET['tz'] ?? '');
if (!Timezone::isValid($tzId)) {
    $tzId = Timezone::guess($lat, $lon);
}
$tz = new DateTimeZone($tzId);

// Erinnerung
$reminder = null;
if (isset($_GET['reminder']) && $_GET['reminder'] !== '') {
    $r = filter_var($_GET['reminder'], FILTER_VALIDATE_INT);
    if ($r !== false && $r > 0 && $r <= 1440) {
        $reminder = $r;
    }
}

// Zeitraum bestimmen
$months = filter_var($_GET['months'] ?? 12, FILTER_VALIDATE_INT) ?: 12;
$months = max(1, min(LH_MAX_MONTHS, $months));

$today   = new DateTimeImmutable('today', $tz);
$rolling = !empty($_GET['rolling']) && $_GET['rolling'] !== '0';
$endRaw  = (string) ($_GET['end'] ?? '');

if (!$rolling && $endRaw !== '') {
    $end = DateTimeImmutable::createFromFormat('!Y-m-d', $endRaw, $tz);
    if ($end === false) {
        LightHours\json_error('Ungültiges Enddatum. Erwartet wird JJJJ-MM-TT.', 422);
    }
    if ($end <= $today) {
        LightHours\json_error('Das Enddatum muss in der Zukunft liegen.', 422);
    }
    // Nach oben begrenzen, damit niemand versehentlich 50 Jahre erzeugt
    $maxEnd = $today->modify('+' . LH_MAX_MONTHS . ' months');
    if ($end > $maxEnd) {
        $end = $maxEnd;
    }
} else {
    $end = $today->modify('+' . $months . ' months');
}

// Abruf anonym vermerken, bevor gerechnet wird – so zählt auch ein Abruf mit,
// bei dem die Erzeugung später scheitern würde.
Stats::record(Stats::fingerprint($lat, $lon, $events, $months, $tzId));

$i18n = new I18n($lang);
$ics  = Ics::build($lat, $lon, $today, $end, $events, $tz, $i18n, $reminder, $name);

// Dateiname aus dem Ortsnamen ableiten
$slug = strtolower((string) preg_replace('/[^A-Za-z0-9]+/', '-', $name));
$slug = trim($slug, '-');
$file = 'lighthours' . ($slug !== '' ? '-' . $slug : '') . '.ics';

header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Content-Length: ' . strlen($ics));
header('Cache-Control: public, max-age=43200'); // 12 Stunden
header('Access-Control-Allow-Origin: *');

echo $ics;
