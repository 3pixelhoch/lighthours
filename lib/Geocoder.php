<?php
/**
 * Ortssuche über OpenStreetMap / Nominatim.
 *
 * Die Anfrage läuft bewusst über den Server und nicht aus dem Browser:
 * so bleibt die IP-Adresse der Nutzer bei Nominatim unbekannt, es gibt keine
 * CORS-Probleme und der Zwischenspeicher entlastet den kostenlosen Dienst.
 */

declare(strict_types=1);

namespace LightHours;

final class Geocoder
{
    /** Fehlercode: Kontaktadresse fehlt – kein vorübergehendes Problem */
    public const ERROR_NOT_CONFIGURED = 1001;

    /**
     * Freitextsuche: Stadt, Adresse, Postleitzahl oder Region.
     *
     * @return array<int, array{name:string, short:string, lat:float, lon:float,
     *                          country:string, timezone:string, timezones:string[]}>
     *
     * @throws \RuntimeException wenn der Dienst nicht erreichbar ist
     */
    public static function search(
        string $query,
        string $lang = 'de',
        int $limit = 6,
        string $preferCountry = ''
    ): array {
        if (!\LightHours\user_agent_configured()) {
            throw new \RuntimeException(
                'LH_USER_AGENT in lib/config.php enthält noch die Platzhalteradresse. '
                . 'Nominatim lehnt solche Anfragen ab. Bitte eine echte Kontaktadresse eintragen.',
                self::ERROR_NOT_CONFIGURED
            );
        }

        $query = trim($query);
        if (mb_strlen($query) < 2) {
            return [];
        }

        $cacheKey = 'geo_' . sha1($query . '|' . $lang . '|' . $limit . '|' . $preferCountry);
        $cached   = Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        $url = LH_NOMINATIM_URL . '/search?' . http_build_query([
            'q'              => $query,
            'format'         => 'jsonv2',
            'limit'          => $limit,
            'accept-language'=> $lang,
            'addressdetails' => 1,
        ]);

        $raw     = self::fetch($url);
        $decoded = json_decode($raw, true);

        if (!is_array($decoded)) {
            throw new \RuntimeException('Unerwartete Antwort der Ortssuche.');
        }

        $results = [];
        foreach ($decoded as $item) {
            if (!isset($item['lat'], $item['lon'])) {
                continue;
            }

            $lat     = (float) $item['lat'];
            $lon     = (float) $item['lon'];
            $addr    = is_array($item['address'] ?? null) ? $item['address'] : [];
            $country = strtoupper((string) ($addr['country_code'] ?? ''));
            $zones   = Timezone::forCountry($country);

            $results[] = [
                'name'      => (string) ($item['display_name'] ?? ''),
                'short'     => self::label($item, $addr),
                'region'    => self::region($addr),
                'lat'       => $lat,
                'lon'       => $lon,
                'country'   => $country,
                'timezone'  => Timezone::guess($lat, $lon, $country, $zones),
                'timezones' => $zones,
            ];
        }

        $results = self::preferCountry($results, $preferCountry);

        Cache::set($cacheKey, $results);

        return $results;
    }

    /**
     * Beschriftung eines Treffers.
     *
     * Bei Postleitzahlen ist der reine Name nur die Ziffernfolge – „20095“ sagt
     * niemandem etwas und gibt es weltweit mehrfach. Deshalb kommt der Ort dazu.
     */
    private static function label(array $item, array $addr): string
    {
        $name = trim((string) ($item['name'] ?? ''));

        if (($item['addresstype'] ?? '') === 'postcode') {
            $ort = '';
            foreach (['city', 'town', 'village', 'municipality', 'county'] as $key) {
                if (!empty($addr[$key])) {
                    $ort = (string) $addr[$key];
                    break;
                }
            }
            if ($ort !== '') {
                return trim($name . ' ' . $ort);
            }
        }

        if ($name !== '') {
            return $name;
        }

        return trim(explode(',', (string) ($item['display_name'] ?? ''))[0]);
    }

    /** Zweite Zeile eines Treffers: Region und Land, damit Gleichnamiges unterscheidbar wird */
    private static function region(array $addr): string
    {
        $teile = [];
        foreach (['state', 'country'] as $key) {
            if (!empty($addr[$key])) {
                $teile[] = (string) $addr[$key];
            }
        }

        return implode(', ', $teile);
    }

    /**
     * Treffer aus dem erwarteten Land nach vorn sortieren.
     *
     * Postleitzahlen sind weltweit mehrdeutig: „20095“ liegt in Italien, in
     * Deutschland und – als 20-095 – in Polen. Ohne diese Gewichtung landet der
     * Nutzer schnell auf dem falschen Kontinent. Die übrige Reihenfolge von
     * Nominatim bleibt erhalten.
     *
     * @param  array<int, array<string, mixed>> $results
     * @return array<int, array<string, mixed>>
     */
    private static function preferCountry(array $results, string $preferCountry): array
    {
        $cc = strtoupper(trim($preferCountry));
        if ($cc === '' || count($results) < 2) {
            return $results;
        }

        $treffer = [];
        $rest    = [];
        foreach ($results as $r) {
            if (($r['country'] ?? '') === $cc) {
                $treffer[] = $r;
            } else {
                $rest[] = $r;
            }
        }

        return array_merge($treffer, $rest);
    }

    /**
     * HTTP-Abruf – bevorzugt cURL, sonst Streams.
     * Beides ist auf praktisch jedem Webhosting vorhanden.
     */
    private static function fetch(string $url): string
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 12,
                CURLOPT_CONNECTTIMEOUT => 6,
                CURLOPT_USERAGENT      => LH_USER_AGENT,
                CURLOPT_HTTPHEADER     => ['Accept: application/json'],
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS      => 3,
            ]);
            $body   = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            $error  = curl_error($ch);
            curl_close($ch);

            if ($status === 403) {
                throw new \RuntimeException(
                    'Nominatim hat die Anfrage abgelehnt (HTTP 403). Meist liegt das an der '
                    . 'Kontaktadresse in LH_USER_AGENT oder an zu vielen Anfragen.',
                    self::ERROR_NOT_CONFIGURED
                );
            }
            if ($body === false || $status >= 400) {
                throw new \RuntimeException('Ortssuche nicht erreichbar: ' . ($error ?: "HTTP {$status}"));
            }

            return (string) $body;
        }

        if (!ini_get('allow_url_fopen')) {
            throw new \RuntimeException(
                'Weder cURL noch allow_url_fopen verfügbar – die Ortssuche kann nicht arbeiten.'
            );
        }

        $context = stream_context_create([
            'http' => [
                'method'  => 'GET',
                'header'  => "User-Agent: " . LH_USER_AGENT . "\r\nAccept: application/json\r\n",
                'timeout' => 12,
            ],
        ]);

        $body = @file_get_contents($url, false, $context);
        if ($body === false) {
            throw new \RuntimeException('Ortssuche nicht erreichbar.');
        }

        return $body;
    }
}
