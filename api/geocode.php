<?php
/**
 * GET /api/geocode.php?q=&lang=
 *
 * Ortssuche. Läuft über den Server, damit die IP-Adresse der Besucher
 * den Kartendienst nicht erreicht.
 */

declare(strict_types=1);

require_once __DIR__ . '/../lib/bootstrap.php';

use LightHours\Geocoder;

$q    = trim((string) ($_GET['q'] ?? ''));
$lang = (string) ($_GET['lang'] ?? LH_DEFAULT_LANG);

// Land für die Gewichtung: ausdrücklich übergeben oder aus dem Browser abgeleitet
$country = (string) ($_GET['country'] ?? '');
if (!preg_match('/^[A-Za-z]{2}$/', $country)) {
    $country = LightHours\preferred_country();
}

if (mb_strlen($q) < 2) {
    LightHours\json_error('Suchbegriff zu kurz.', 422);
}

try {
    $results = Geocoder::search(mb_substr($q, 0, 200), $lang, 6, $country);
} catch (\RuntimeException $e) {
    // Fehlende Einrichtung ist kein vorübergehender Ausfall – eigener Status,
    // damit die Oberfläche den Unterschied zeigen kann.
    $setup = $e->getCode() === Geocoder::ERROR_NOT_CONFIGURED;
    LightHours\json_response(
        ['error' => $e->getMessage(), 'setup' => $setup],
        $setup ? 503 : 502
    );
}

LightHours\json_response(['query' => $q, 'country' => $country, 'results' => $results]);
