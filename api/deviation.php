<?php
/**
 * GET /api/deviation.php?lat=&radius=
 *
 * Maximale zeitliche Abweichung der Lichtphasen innerhalb eines Radius.
 */

declare(strict_types=1);

require_once __DIR__ . '/../lib/bootstrap.php';

use LightHours\LightPhases;

$lat = filter_input(INPUT_GET, 'lat', FILTER_VALIDATE_FLOAT);
if ($lat === false || $lat === null || $lat < -90 || $lat > 90) {
    LightHours\json_error('Parameter lat fehlt oder ist ungültig.', 422);
}

$radius = filter_input(INPUT_GET, 'radius', FILTER_VALIDATE_FLOAT);
if ($radius === false || $radius === null || $radius <= 0 || $radius > 500) {
    LightHours\json_error('Parameter radius muss zwischen 1 und 500 liegen.', 422);
}

LightHours\json_response([
    'lat'                    => $lat,
    'radius_km'              => $radius,
    'max_deviation_minutes'  => LightPhases::maxDeviationMinutes((float) $lat, (float) $radius),
]);
