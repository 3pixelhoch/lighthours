<?php
/**
 * GET /api/times.php?lat=&lon=&date=&days=&tz=&events=&lang=
 *
 * Lichtzeiten für einen oder mehrere Tage als JSON.
 */

declare(strict_types=1);

require_once __DIR__ . '/../lib/bootstrap.php';

use LightHours\I18n;
use LightHours\LightPhases;
use LightHours\Timezone;

[$lat, $lon] = LightHours\read_coords();

$events = LightHours\read_events($_GET['events'] ?? null);
$i18n   = new I18n((string) ($_GET['lang'] ?? LH_DEFAULT_LANG));

$tzId = (string) ($_GET['tz'] ?? '');
if (!Timezone::isValid($tzId)) {
    $tzId = Timezone::guess($lat, $lon);
}
$tz = new DateTimeZone($tzId);

$days = filter_var($_GET['days'] ?? 1, FILTER_VALIDATE_INT) ?: 1;
$days = max(1, min(31, $days));

$dateRaw = (string) ($_GET['date'] ?? '');
if ($dateRaw !== '') {
    $from = DateTimeImmutable::createFromFormat('!Y-m-d', $dateRaw, $tz);
    if ($from === false) {
        LightHours\json_error('Ungültiges Datum. Erwartet wird JJJJ-MM-TT.', 422);
    }
} else {
    $from = new DateTimeImmutable('today', $tz);
}

$out = [];
for ($i = 0; $i < $days; $i++) {
    $day    = $from->modify("+{$i} day");
    $phases = [];

    foreach (LightPhases::forDay($day, $lat, $lon, $events, $tz) as $p) {
        $start = (new DateTimeImmutable('@' . $p['start']))->setTimezone($tz);
        $end   = (new DateTimeImmutable('@' . $p['end']))->setTimezone($tz);

        $phases[] = [
            'event'    => $p['event'],
            'label'    => $i18n->t('event.' . $p['event']),
            'start'    => $start->format('c'),
            'end'      => $end->format('c'),
            'start_local' => $start->format('H:i'),
            'end_local'   => $end->format('H:i'),
            'duration_minutes' => (int) round(($p['end'] - $p['start']) / 60),
        ];
    }

    $out[] = ['date' => $day->format('Y-m-d'), 'phases' => $phases];
}

LightHours\json_response([
    'lat'      => $lat,
    'lon'      => $lon,
    'timezone' => $tzId,
    'days'     => $out,
]);
