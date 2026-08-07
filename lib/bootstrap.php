<?php
/**
 * Gemeinsamer Einstiegspunkt: Konfiguration, Autoloading, Hilfsfunktionen.
 * Wird von jeder öffentlich erreichbaren Datei als Erstes eingebunden.
 */

declare(strict_types=1);

namespace LightHours;

require_once __DIR__ . '/config.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'LightHours\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $file = __DIR__ . '/' . substr($class, strlen($prefix)) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

mb_internal_encoding('UTF-8');
date_default_timezone_set('UTC');

/*
 * Kürzeste Darstellung von Fließkommazahlen in JSON.
 *
 * Steht serialize_precision auf 17 – auf manchen Webspaces die Voreinstellung –,
 * liefert die API 53.55109999999999814690454513765871524810791015625 statt
 * 53.5511. Zahlenwert und Rundung sind identisch, aber lesbar ist es nicht.
 * Mit -1 wählt PHP die kürzeste Schreibweise, die verlustfrei zurückgelesen wird.
 */
ini_set('serialize_precision', '-1');

/**
 * Basis-URL der Installation ermitteln (für Abo-Links).
 * Kann in config.php fest gesetzt werden, sonst automatisch aus der Anfrage.
 */
function base_url(): string
{
    if (defined('LH_BASE_URL') && LH_BASE_URL !== '') {
        return rtrim(LH_BASE_URL, '/');
    }

    $https  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
           || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'
           || ($_SERVER['SERVER_PORT'] ?? '') === '443';
    $scheme = $https ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir    = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');

    return $scheme . '://' . $host . $dir;
}

/** JSON-Antwort senden und beenden */
function json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/** Fehlerantwort als JSON */
function json_error(string $message, int $status = 400): never
{
    json_response(['error' => $message], $status);
}

/** Koordinaten aus der Anfrage lesen und prüfen */
function read_coords(): array
{
    $lat = filter_input(INPUT_GET, 'lat', FILTER_VALIDATE_FLOAT);
    $lon = filter_input(INPUT_GET, 'lon', FILTER_VALIDATE_FLOAT);

    if ($lat === false || $lat === null || $lon === false || $lon === null) {
        json_error('Parameter lat und lon sind erforderlich.', 422);
    }
    if ($lat < -90 || $lat > 90 || $lon < -180 || $lon > 180) {
        json_error('Koordinaten außerhalb des gültigen Bereichs.', 422);
    }

    return [(float) $lat, (float) $lon];
}

/**
 * Terminarten aus der Anfrage lesen.
 *
 * @return string[]
 */
function read_events(?string $raw): array
{
    if ($raw === null || trim($raw) === '') {
        return LightPhases::EVENTS;
    }

    $wanted = array_values(array_intersect(
        array_map('trim', explode(',', $raw)),
        LightPhases::EVENTS
    ));

    return $wanted === [] ? LightPhases::EVENTS : $wanted;
}

/**
 * Adresse, unter der sich falsche Zeiten melden lassen.
 *
 * Wird aus der Quellcode-Adresse abgeleitet, damit es keine zweite Einstellung
 * gibt, die man vergessen kann. Wer das Projekt ohne öffentliches Repository
 * betreibt, bekommt keinen Verweis ins Leere: dann ist der Rückgabewert leer
 * und der Hinweis verschwindet.
 */
function report_url(): string
{
    if (LH_SOURCE_URL === '') {
        return '';
    }

    return rtrim(LH_SOURCE_URL, '/') . (str_contains(LH_SOURCE_URL, 'github.com') ? '/issues' : '');
}

/**
 * Gewünschte Wochentage lesen (ISO 8601: 1 = Montag ... 7 = Sonntag).
 *
 * Leer oder unbrauchbar bedeutet: alle Tage. Ein Kalender ohne Tage wäre leer,
 * und ein leeres Abo sieht für den Nutzer aus wie ein Fehler.
 *
 * @return int[]|null null = keine Einschränkung
 */
function read_weekdays(?string $raw): ?array
{
    if ($raw === null || trim($raw) === '') {
        return null;
    }

    $tage = [];
    foreach (explode(',', $raw) as $teil) {
        $n = filter_var(trim($teil), FILTER_VALIDATE_INT);
        if ($n !== false && $n >= 1 && $n <= 7) {
            $tage[$n] = true;
        }
    }

    $tage = array_keys($tage);
    sort($tage);

    return ($tage === [] || count($tage) === 7) ? null : $tage;
}

/**
 * Ist in LH_USER_AGENT eine echte Kontaktadresse hinterlegt?
 *
 * Nominatim sperrt Anfragen mit Platzhalteradressen mit HTTP 403 ab
 * ("Access denied"). Ohne diese Prüfung äußert sich der Fehler nur als
 * wortlose Fehlermeldung im Formular – die häufigste Stolperfalle beim
 * ersten Aufsetzen.
 */
function user_agent_configured(): bool
{
    $ua = defined('LH_USER_AGENT') ? trim(LH_USER_AGENT) : '';

    if ($ua === '') {
        return false;
    }
    foreach (['example.org', 'example.com', 'example.net', 'deine-domain', 'deine@'] as $platzhalter) {
        if (stripos($ua, $platzhalter) !== false) {
            return false;
        }
    }

    return true;
}

/**
 * Wahrscheinliches Land des Besuchers aus dem Accept-Language-Header.
 *
 * "de-DE,de;q=0.9" ergibt DE. Das dient allein dazu, bei mehrdeutigen Eingaben
 * wie Postleitzahlen die naheliegenden Treffer nach vorn zu holen – es wird
 * nichts gespeichert und nichts gefiltert.
 */
function preferred_country(): string
{
    $header = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';

    foreach (explode(',', $header) as $teil) {
        $code = trim(explode(';', $teil)[0]);
        if (preg_match('/^[a-z]{2}-([A-Za-z]{2})$/', $code, $m)) {
            return strtoupper($m[1]);
        }
    }

    // Kein Regionskürzel im Header: aus der Sprache das häufigste Land ableiten
    $fallback = ['de' => 'DE', 'at' => 'AT', 'fr' => 'FR', 'it' => 'IT', 'es' => 'ES', 'nl' => 'NL'];
    foreach (explode(',', $header) as $teil) {
        $code = strtolower(substr(trim(explode(';', $teil)[0]), 0, 2));
        if (isset($fallback[$code])) {
            return $fallback[$code];
        }
    }

    return '';
}

/**
 * Ein zur Laufzeit erzeugtes Verzeichnis vor Zugriff aus dem Web schützen.
 *
 * Betrifft nur cache/. Der Ordner entsteht beim ersten Abruf und kann deshalb
 * nicht im Auslieferungsstand mitgeschützt werden. Er enthält nichts
 * Persönliches, aber Zwischenergebnisse gehören nicht ins Web.
 */
function verzeichnis_schuetzen(string $dir): void
{
    $datei = $dir . '/.htaccess';
    if ($dir === '' || !is_dir($dir) || is_file($datei)) {
        return;
    }

    // Beide Schreibweisen, weil Apache 2.4 und 2.2 sich unterscheiden.
    // Die IfModule-Klammern verhindern einen Serverfehler, falls eines fehlt.
    @file_put_contents($datei, implode("\n", [
        '<IfModule mod_authz_core.c>',
        '  Require all denied',
        '</IfModule>',
        '<IfModule !mod_authz_core.c>',
        '  Order allow,deny',
        '  Deny from all',
        '</IfModule>',
    ]) . "\n");
}

/** HTML-sicher ausgeben */
function h(?string $text): string
{
    return htmlspecialchars((string) $text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
