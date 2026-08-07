<?php
/**
 * Goldene und Blaue Stunde aus Sonnenhöhen ableiten.
 *
 * Definitionen (Sonnenhöhe über dem Horizont):
 *   Goldene Stunde  -4° … +6°
 *   Blaue Stunde    -6° … -4°
 */

declare(strict_types=1);

namespace LightHours;

final class LightPhases
{
    public const EVENTS = ['blue_morning', 'golden_morning', 'golden_evening', 'blue_evening'];

    /** Sonnenhöhen der Phasengrenzen in Grad */
    private const BOUNDS = [
        'blue_morning'   => [-6.0, -4.0, true],
        'golden_morning' => [-4.0,  6.0, true],
        'golden_evening' => [ 6.0, -4.0, false],
        'blue_evening'   => [-4.0, -6.0, false],
    ];

    /**
     * Geometrische Sonnenhöhe für Auf- und Untergang.
     *
     * -0,833 Grad ist die übliche Festlegung: Der Sonnenrand berührt den
     * Horizont, nicht der Mittelpunkt. Enthält den scheinbaren Radius der
     * Sonnenscheibe und die mittlere Refraktion von 34 Bogenminuten.
     *
     * Deshalb wird hier ohne Refraktion gerechnet: Sie steckt in den -0,833
     * Grad bereits drin. Wer sie zusätzlich anwendet, liegt zweieinhalb
     * Minuten daneben - und das fällt beim Vergleich mit jeder Wetter-App auf.
     */
    private const HORIZONT_GRAD = -0.833;

    /**
     * Lichtphasen eines Kalendertages am Ort.
     *
     * Wichtig: Bezugspunkt ist der *örtliche* Mittag, nicht 12:00 UTC – sonst
     * rutschen Termine in Zeitzonen fernab von Greenwich auf den Vor- oder
     * Folgetag.
     *
     * @param string[]|null $events Gewünschte Phasen, null = alle
     *
     * @return array<int, array{event:string, start:int, end:int}>
     *         Zeitstempel in UTC, chronologisch sortiert.
     *         Phasen, die an dem Tag nicht existieren (Polartag/-nacht), fehlen.
     */
    public static function forDay(
        \DateTimeImmutable $day,
        float $lat,
        float $lon,
        ?array $events = null,
        ?\DateTimeZone $tz = null
    ): array {
        $noon = (new \DateTimeImmutable(
            $day->format('Y-m-d') . ' 12:00:00',
            $tz ?? new \DateTimeZone('UTC')
        ))->getTimestamp();

        $wanted = $events ?? self::EVENTS;
        $result = [];

        foreach ($wanted as $event) {
            if (!isset(self::BOUNDS[$event])) {
                continue;
            }
            [$fromDeg, $toDeg, $rising] = self::BOUNDS[$event];

            $start = Sun::timeAtAltitude($fromDeg, $rising, $noon, $lat, $lon);
            $end   = Sun::timeAtAltitude($toDeg, $rising, $noon, $lat, $lon);

            // Beide Grenzen müssen an diesem Tag erreicht werden
            if ($start === null || $end === null || $end <= $start) {
                continue;
            }

            // Auf- beziehungsweise Untergang als Bezugspunkt mitgeben.
            // Die Goldene Stunde endet abends bei -4 Grad, also gut 25 Minuten
            // nach Sonnenuntergang. Ohne diese Angabe wirkt der Termin falsch:
            // Man steht draußen, es ist längst dämmrig, und der Kalender sagt
            // "Goldene Stunde".
            $horizont = Sun::timeAtAltitude(self::HORIZONT_GRAD, $rising, $noon, $lat, $lon, false);

            $result[] = [
                'event'   => $event,
                'start'   => $start,
                'end'     => $end,
                'horizon' => $horizont,
                'rising'  => $rising,
            ];
        }

        usort($result, static fn(array $a, array $b): int => $a['start'] <=> $b['start']);

        return $result;
    }

    /**
     * Lichtphasen für einen Zeitraum.
     *
     * @return array<int, array{event:string, start:int, end:int}>
     */
    public static function forRange(
        \DateTimeImmutable $from,
        \DateTimeImmutable $to,
        float $lat,
        float $lon,
        ?array $events = null,
        ?\DateTimeZone $tz = null,
        ?array $weekdays = null
    ): array {
        $all  = [];
        $day  = $from->setTime(0, 0);
        $last = $to->setTime(0, 0);

        while ($day <= $last) {
            // Wochentage werden vor der Rechnung geprüft, nicht danach: Ein Abo
            // über zwei Jahre mit allen vier Phasen umfasst 2928 Termine, und
            // ein zugeschütteter Kalender ist der häufigste Grund, ein Abo
            // wieder zu löschen. Wer nur Freitag bis Sonntag braucht, spart
            // sich fünf Siebtel davon - und der Server die Rechenarbeit.
            if ($weekdays !== null && !in_array((int) $day->format('N'), $weekdays, true)) {
                $day = $day->modify('+1 day');
                continue;
            }

            foreach (self::forDay($day, $lat, $lon, $events, $tz) as $phase) {
                $all[] = $phase;
            }
            $day = $day->modify('+1 day');
        }

        return $all;
    }

    /**
     * Grobe maximale zeitliche Abweichung innerhalb eines Radius.
     *
     * Ost-West-Verschiebung: 4 Minuten pro Längengrad; ein Längengrad ist
     * cos(Breite) × 111,32 km breit. Nord-Süd-Effekte sind jahreszeitabhängig
     * und meist kleiner – als Sicherheitsaufschlag 25 %.
     */
    public static function maxDeviationMinutes(float $lat, float $radiusKm): int
    {
        $kmPerDegLon = 111.32 * max(cos(deg2rad($lat)), 0.05);
        $minutes     = ($radiusKm / $kmPerDegLon) * 4 * 1.25;

        return max(1, (int) round($minutes));
    }
}
