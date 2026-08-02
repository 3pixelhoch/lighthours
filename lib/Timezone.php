<?php
/**
 * Zeitzonen-Ermittlung ohne externe Bibliothek.
 *
 * Ansatz: Nominatim liefert zu jedem Ort den Ländercode. PHP kennt über die
 * eingebaute Zeitzonendatenbank alle Zonen eines Landes. Für die allermeisten
 * Länder ist das genau eine – damit ist die Zone eindeutig bestimmt.
 *
 * Bei Ländern mit mehreren Zonen (USA, Russland, Australien …) wird anhand der
 * geografischen Länge die plausibelste Zone vorgeschlagen; der Nutzer kann im
 * Formular korrigieren. So bleibt das Projekt frei von 50-MB-Geodaten.
 */

declare(strict_types=1);

namespace LightHours;

final class Timezone
{
    /**
     * Alle Zeitzonen eines Landes.
     *
     * @return string[] leer, wenn der Ländercode unbekannt ist
     */
    public static function forCountry(string $countryCode): array
    {
        $cc = strtoupper(trim($countryCode));
        if (strlen($cc) !== 2) {
            return [];
        }

        return \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $cc) ?: [];
    }

    /**
     * Beste Zeitzone für einen Ort bestimmen.
     *
     * Mit Ländercode ist das Ergebnis in den meisten Fällen eindeutig. Ohne
     * Ländercode wird unter allen bekannten Zonen die geografisch nächste
     * gewählt – die PHP-Zeitzonendatenbank enthält zu jeder Zone Koordinaten.
     * Das ist deutlich besser als eine Etc/GMT-Zone, die keine Sommerzeit
     * kennt und damit im Sommer eine Stunde danebenliegen würde.
     *
     * Hinweis: Ohne Ländercode kann die Kennung eine Nachbarstadt benennen
     * (München → Europe/Vaduz, Kapstadt → Africa/Maseru). Versatz und
     * Sommerzeitregeln sind dabei identisch, die Uhrzeiten stimmen also –
     * lediglich die Beschriftung wirkt ungewohnt. Über die Ortssuche wird
     * immer ein Ländercode mitgeliefert, dort tritt der Fall nicht auf.
     *
     * @param string[] $candidates Vorgabe, sonst aus dem Ländercode abgeleitet
     */
    public static function guess(float $lat, float $lon, string $countryCode = '', array $candidates = []): string
    {
        if ($candidates === []) {
            $candidates = self::forCountry($countryCode);
        }

        if (count($candidates) === 1) {
            return $candidates[0];
        }

        if ($candidates === []) {
            $candidates = \DateTimeZone::listIdentifiers() ?: ['UTC'];
        }

        // Zonen mit gleichem Verhalten zusammenfassen. Deutschland führt neben
        // Europe/Berlin auch Europe/Busingen – dieselbe Uhrzeit, aber als
        // Beschriftung für München unbrauchbar. Verhalten sich alle Kandidaten
        // gleich, gilt der erste Eintrag: Die Zeitzonendatenbank nennt je Land
        // die maßgebliche Zone zuerst.
        $signaturen = [];
        foreach ($candidates as $id) {
            $signaturen[$id] = self::signature($id);
        }

        if (count(array_unique($signaturen)) === 1) {
            return $candidates[0];
        }

        // Verhalten unterscheidet sich wirklich (USA, Russland, Australien):
        // geografisch nächste Zone bestimmt, welche Uhrzeit gilt.
        $naechste = self::nearest($lat, $lon, $candidates);

        // Unter den gleich tickenden Zonen die geläufigste Schreibweise wählen.
        // Die Datenbank kennt neben America/New_York auch America/Kentucky/Louisville
        // – gleiche Zeit, aber als Beschriftung ungewohnt. Zonen der Form
        // Region/Stadt stehen für das Hauptgebiet, tiefere Pfade für Sonderfälle.
        $gleichwertig = array_values(array_filter(
            $candidates,
            static fn(string $id): bool => $signaturen[$id] === $signaturen[$naechste]
        ));

        // Nicht-geografische Kennungen aussortieren. Ohne Landescode besteht die
        // Kandidatenliste aus allen Zonen der Welt, und dann gewinnt die
        // Tiefenregel unten immer UTC oder Etc/GMT – sie haben keinen Schrägstrich.
        // Für Reykjavík kam so "UTC" heraus: zeitlich richtig, als Beschriftung
        // eines Kalenders aber falsch.
        $geografisch = array_values(array_filter(
            $gleichwertig,
            static fn(string $id): bool => str_contains($id, '/') && !str_starts_with($id, 'Etc/')
        ));
        if ($geografisch !== []) {
            $gleichwertig = $geografisch;
        }

        $minTiefe = min(array_map(static fn(string $id): int => substr_count($id, '/'), $gleichwertig));
        $flach    = array_values(array_filter(
            $gleichwertig,
            static fn(string $id): bool => substr_count($id, '/') === $minTiefe
        ));

        return count($flach) === 1 ? $flach[0] : self::nearest($lat, $lon, $flach);
    }

    /**
     * Fingerabdruck des Zeitverhaltens einer Zone.
     *
     * Zwei Zonen mit gleicher Signatur zeigen über den geprüften Zeitraum immer
     * dieselbe Uhrzeit – sie sind für Nutzer nicht unterscheidbar.
     */
    private static function signature(string $id): string
    {
        static $cache = [];
        if (isset($cache[$id])) {
            return $cache[$id];
        }

        try {
            $tz = new \DateTimeZone($id);
        } catch (\Exception) {
            return $cache[$id] = '?';
        }

        // Zwei Jahre in Halbmonatsschritten – erfasst jede Sommerzeitregel
        $werte = [];
        $t     = time();
        for ($i = 0; $i < 48; $i++) {
            $werte[] = $tz->getOffset(new \DateTimeImmutable('@' . ($t + $i * 1296000)));
        }

        return $cache[$id] = implode(',', $werte);
    }

    /**
     * Geografisch nächstgelegene Zeitzone aus einer Kandidatenliste.
     *
     * @param string[] $candidates
     */
    private static function nearest(float $lat, float $lon, array $candidates): string
    {
        $best     = $candidates[0];
        $bestDist = INF;

        foreach ($candidates as $id) {
            try {
                $loc = (new \DateTimeZone($id))->getLocation();
            } catch (\Exception) {
                continue;
            }
            if (!is_array($loc) || !isset($loc['latitude'], $loc['longitude'])) {
                continue;
            }

            $dist = self::distance($lat, $lon, (float) $loc['latitude'], (float) $loc['longitude']);
            if ($dist < $bestDist) {
                $bestDist = $dist;
                $best     = $id;
            }
        }

        return $best;
    }

    /** Entfernung zweier Punkte auf der Kugel (Haversine, in Kilometern) */
    private static function distance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return 6371.0 * 2 * asin(min(1.0, sqrt($a)));
    }

    /** Prüfen, ob eine Kennung gültig ist */
    public static function isValid(string $id): bool
    {
        if ($id === '') {
            return false;
        }
        try {
            new \DateTimeZone($id);

            return true;
        } catch (\Exception) {
            return false;
        }
    }
}
