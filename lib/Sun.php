<?php
/**
 * Sonnenstandsberechnung.
 *
 * Verfahren nach den astronomischen Standardformeln (Jean Meeus,
 * "Astronomical Algorithms") mit der NOAA-Refraktionskorrektur.
 *
 * Gerechnet wird mit der *scheinbaren* Sonnenhöhe, also inklusive der
 * Lichtbrechung in der Atmosphäre – das ist die Höhe, unter der die Sonne
 * tatsächlich am Himmel steht. Nahe am Horizont macht das gut zwei Minuten
 * Unterschied.
 *
 * Der Zeitpunkt wird zweistufig bestimmt: eine geschlossene Näherung liefert
 * den Startwert, anschließend verfeinert ein Sekantenverfahren auf die Sekunde.
 *
 * Keine externen Bibliotheken, kein Composer.
 */

declare(strict_types=1);

namespace LightHours;

final class Sun
{
    private const RAD   = M_PI / 180.0;
    private const J2000 = 2451545.0;
    private const J1970 = 2440588.0;

    /** Schiefe der Ekliptik */
    private const OBLIQUITY = 23.4397 * self::RAD;

    /** Unix-Zeitstempel → Julianisches Datum */
    private static function toJulian(int $ts): float
    {
        return $ts / 86400.0 - 0.5 + self::J1970;
    }

    /** Julianisches Datum → Unix-Zeitstempel */
    private static function fromJulian(float $j): int
    {
        return (int) round(($j + 0.5 - self::J1970) * 86400.0);
    }

    /** Mittlere Anomalie der Sonne */
    private static function meanAnomaly(float $d): float
    {
        return self::RAD * (357.5291 + 0.98560028 * $d);
    }

    /** Ekliptikale Länge der Sonne */
    private static function eclipticLongitude(float $m): float
    {
        $c = self::RAD * (1.9148 * sin($m) + 0.02 * sin(2 * $m) + 0.0003 * sin(3 * $m));
        $p = self::RAD * 102.9372; // Länge des Perihels

        return $m + $c + $p + M_PI;
    }

    /** Deklination der Sonne */
    private static function declination(float $l): float
    {
        return asin(sin(self::OBLIQUITY) * sin($l));
    }

    /**
     * Atmosphärische Refraktion (NOAA).
     *
     * @param  float $elevDeg geometrische Sonnenhöhe in Grad
     * @return float Korrektur in Grad, die zur geometrischen Höhe addiert wird
     */
    private static function refraction(float $elevDeg): float
    {
        if ($elevDeg >= 85.0) {
            return 0.0;
        }

        $te = tan(deg2rad($elevDeg));

        if ($elevDeg > 5.0) {
            $arcsec = 58.1 / $te - 0.07 / ($te ** 3) + 0.000086 / ($te ** 5);
        } elseif ($elevDeg > -0.575) {
            $s1     = -12.79 + $elevDeg * 0.711;
            $s2     = 103.4 + $elevDeg * $s1;
            $s3     = -518.2 + $elevDeg * $s2;
            $arcsec = 1735.0 + $elevDeg * $s3;
        } else {
            $arcsec = -20.774 / $te;
        }

        return $arcsec / 3600.0;
    }

    /**
     * Sonnenhöhe über dem Horizont in Grad – vollständiger NOAA-Algorithmus.
     *
     * Deutlich genauer als die verkürzten Näherungsformeln: Abweichung
     * gegenüber Referenzimplementierungen deutlich unter einer Bogenminute.
     *
     * @param bool $withRefraction true = scheinbare Höhe (Standard),
     *                             false = rein geometrische Höhe
     */
    public static function altitude(int $ts, float $lat, float $lon, bool $withRefraction = true): float
    {
        $jd = self::toJulian($ts);
        $t  = ($jd - self::J2000) / 36525.0; // Julianische Jahrhunderte seit J2000

        // Mittlere Länge und mittlere Anomalie der Sonne
        $l0 = fmod(280.46646 + $t * (36000.76983 + $t * 0.0003032), 360.0);
        if ($l0 < 0) {
            $l0 += 360.0;
        }
        $m = 357.52911 + $t * (35999.05029 - 0.0001537 * $t);

        // Exzentrizität der Erdbahn
        $e = 0.016708634 - $t * (0.000042037 + 0.0000001267 * $t);

        // Mittelpunktsgleichung
        $c = sin(deg2rad($m)) * (1.914602 - $t * (0.004817 + 0.000014 * $t))
           + sin(deg2rad(2 * $m)) * (0.019993 - 0.000101 * $t)
           + sin(deg2rad(3 * $m)) * 0.000289;

        // Scheinbare ekliptikale Länge (inkl. Nutation und Aberration)
        $trueLong = $l0 + $c;
        $omega    = 125.04 - 1934.136 * $t;
        $appLong  = $trueLong - 0.00569 - 0.00478 * sin(deg2rad($omega));

        // Schiefe der Ekliptik mit Korrektur
        $meanObliq = 23.0 + (26.0 + (21.448 - $t * (46.815 + $t * (0.00059 - $t * 0.001813))) / 60.0) / 60.0;
        $obliqCorr = $meanObliq + 0.00256 * cos(deg2rad($omega));

        // Deklination
        $decl = rad2deg(asin(sin(deg2rad($obliqCorr)) * sin(deg2rad($appLong))));

        // Zeitgleichung (Minuten)
        $varY  = tan(deg2rad($obliqCorr / 2.0)) ** 2;
        $eqTime = 4.0 * rad2deg(
            $varY * sin(2 * deg2rad($l0))
            - 2 * $e * sin(deg2rad($m))
            + 4 * $e * $varY * sin(deg2rad($m)) * cos(2 * deg2rad($l0))
            - 0.5 * $varY * $varY * sin(4 * deg2rad($l0))
            - 1.25 * $e * $e * sin(2 * deg2rad($m))
        );

        // Wahre Ortszeit → Stundenwinkel
        $minutesUtc     = fmod($ts, 86400) / 60.0;
        $trueSolarTime  = fmod($minutesUtc + $eqTime + 4.0 * $lon, 1440.0);
        if ($trueSolarTime < 0) {
            $trueSolarTime += 1440.0;
        }
        $hourAngle = $trueSolarTime / 4.0 - 180.0;

        $phi  = deg2rad($lat);
        $dec  = deg2rad($decl);
        $ha   = deg2rad($hourAngle);

        $cosZenith = sin($phi) * sin($dec) + cos($phi) * cos($dec) * cos($ha);
        $cosZenith = max(-1.0, min(1.0, $cosZenith));

        $elev = 90.0 - rad2deg(acos($cosZenith));

        return $withRefraction ? $elev + self::refraction($elev) : $elev;
    }

    /**
     * Geschlossene Näherung für den Zeitpunkt einer Sonnenhöhe.
     *
     * @return int|null null, wenn die Höhe an diesem Tag nie erreicht wird
     */
    private static function approximate(
        float $altitudeDeg,
        bool $rising,
        int $dayNoonTs,
        float $lat,
        float $lon
    ): ?int {
        $h   = $altitudeDeg * self::RAD;
        $phi = $lat * self::RAD;
        $lw  = -$lon * self::RAD;

        $d  = self::toJulian($dayNoonTs) - self::J2000;
        $n  = round($d - 0.0009 - $lw / (2 * M_PI));
        $ds = 0.0009 + $lw / (2 * M_PI) + $n;

        $m = self::meanAnomaly($ds);
        $l = self::eclipticLongitude($m);

        $transit = self::J2000 + $ds + 0.0053 * sin($m) - 0.0069 * sin(2 * $l);

        $dec  = self::declination($l);
        $cosH = (sin($h) - sin($phi) * sin($dec)) / (cos($phi) * cos($dec));

        if ($cosH > 1.0 || $cosH < -1.0) {
            return null; // Polartag bzw. Polarnacht
        }

        $w    = acos($cosH);
        $setJ = self::J2000 + (0.0009 + ($w + $lw) / (2 * M_PI) + $n)
              + 0.0053 * sin($m) - 0.0069 * sin(2 * $l);

        return self::fromJulian($rising ? ($transit - ($setJ - $transit)) : $setJ);
    }

    /**
     * Zeitpunkt, zu dem die Sonne eine bestimmte *scheinbare* Höhe erreicht.
     *
     * @param float $altitudeDeg Sonnenhöhe in Grad (negativ = unter dem Horizont)
     * @param bool  $rising      true = aufsteigend (morgens), false = absteigend (abends)
     * @param int   $dayNoonTs   Unix-Zeitstempel des Ortsmittags
     *
     * @return int|null Unix-Zeitstempel (UTC) oder null, wenn nie erreicht
     */
    public static function timeAtAltitude(
        float $altitudeDeg,
        bool $rising,
        int $dayNoonTs,
        float $lat,
        float $lon,
        bool $withRefraction = true
    ): ?int {
        // Startwert: geometrische Näherung, grob um die Refraktion vorkorrigiert
        $t = self::approximate(
            $altitudeDeg - ($withRefraction ? 0.08 : 0.0),
            $rising, $dayNoonTs, $lat, $lon
        );
        if ($t === null) {
            $t = self::approximate($altitudeDeg, $rising, $dayNoonTs, $lat, $lon);
        }
        if ($t === null) {
            return null;
        }

        // Verfeinerung: Sekantenverfahren auf altitude(t) − Zielhöhe
        $t = (float) $t;
        for ($i = 0; $i < 8; $i++) {
            $f  = self::altitude((int) round($t), $lat, $lon, $withRefraction) - $altitudeDeg;
            $f2 = self::altitude((int) round($t) + 60, $lat, $lon, $withRefraction) - $altitudeDeg;

            $slope = ($f2 - $f) / 60.0; // Grad pro Sekunde
            if (abs($slope) < 1e-9) {
                break; // Sonne steht nahezu still (Polarregion)
            }

            $step = $f / $slope;
            // Ausreißer begrenzen, damit das Verfahren nicht wegläuft
            $step = max(-7200.0, min(7200.0, $step));
            $t   -= $step;

            if (abs($step) < 0.5) {
                break;
            }
        }

        $ts     = (int) round($t);
        $reached = abs(self::altitude($ts, $lat, $lon, $withRefraction) - $altitudeDeg);

        // Konvergenz prüfen: Höhe muss tatsächlich getroffen worden sein
        return $reached < 0.05 ? $ts : null;
    }
}
