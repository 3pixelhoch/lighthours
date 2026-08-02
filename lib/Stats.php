<?php
/**
 * Anonyme Zählung aktiver Kalender-Abos.
 *
 * Wie das ohne Datensammlung geht: Ein Abo verrät sich von selbst. Apple,
 * Google und Outlook rufen die Kalender-Adresse regelmäßig ab, um sie aktuell
 * zu halten. Gezählt werden diese Abrufe – genauer: wie viele *verschiedene*
 * Kalender in den letzten Wochen abgerufen wurden.
 *
 * Gespeichert wird ausschließlich ein gekürzter Hash der Kalenderparameter
 * (Koordinaten, Terminarten, Zeitraum). Nicht gespeichert werden:
 *
 *   - IP-Adressen
 *   - Browserkennungen
 *   - Uhrzeiten (nur das Datum, und das nur als Dateiname)
 *   - irgendetwas, das zwei Kalender einer Person zuordnen könnte
 *
 * Der Hash ist mit einem festen Zusatz gesalzen und auf 16 Zeichen gekürzt.
 * Er dient allein dazu, denselben Kalender an zwei Tagen wiederzuerkennen.
 *
 * Was die Zahl bedeutet – und was nicht: Sie zählt **Kalender**, keine
 * Menschen. Wer sich drei Kalender für drei Regionen anlegt, erscheint
 * dreimal. Wer einmal die ICS-Datei herunterlädt, ohne zu abonnieren,
 * erscheint einmal und fällt nach dem Zeitfenster wieder heraus.
 */

declare(strict_types=1);

namespace LightHours;

final class Stats
{
    /** Zeitfenster: Kalender, die länger nicht abgerufen wurden, gelten als still */
    private const FENSTER_TAGE = 30;

    /** Ältere Tagesdateien werden gelöscht */
    private const AUFBEWAHRUNG_TAGE = 40;

    /**
     * Wie lange die errechnete Zahl gilt, bevor neu gezählt wird.
     *
     * Fünf Minuten. Das Nachzählen liest dreißig kleine Textdateien und kostet
     * praktisch nichts; der Zwischenspeicher schützt nur vor Lastspitzen. Bei
     * sehr viel Aufkommen darf der Wert wieder steigen.
     */
    private const ZAEHLUNG_GILT = 300;

    /**
     * Einen Abruf vermerken.
     *
     * @param string $kennung stabile Kennung des Kalenders (siehe fingerprint())
     */
    public static function record(string $kennung): void
    {
        if (!LH_STATS_ENABLED) {
            return;
        }

        $dir = self::dir();
        if ($dir === null) {
            return;
        }

        $datei = $dir . '/' . gmdate('Y-m-d') . '.txt';
        $hash  = substr(hash('sha256', $kennung . '|lighthours-stats'), 0, 16);

        // Innerhalb eines Tages nur einmal vermerken. Bei den erwarteten
        // Größenordnungen ist die Suche im Dateiinhalt günstiger als jede
        // Datenbank – und sie hält die Datei klein.
        $inhalt = is_file($datei) ? (string) @file_get_contents($datei) : '';
        if ($inhalt !== '' && str_contains($inhalt, $hash)) {
            return;
        }

        @file_put_contents($datei, $hash . "\n", FILE_APPEND | LOCK_EX);
        self::aufraeumen($dir);
    }

    /**
     * Wie viele verschiedene Kalender im Zeitfenster abgerufen wurden.
     *
     * Das Ergebnis wird eine Stunde zwischengespeichert – die Zahl ändert sich
     * ohnehin langsam, und jeder Seitenaufruf soll nicht 30 Dateien lesen.
     */
    public static function activeCalendars(): int
    {
        if (!LH_STATS_ENABLED) {
            return 0;
        }

        $dir = self::dir();
        if ($dir === null) {
            return 0;
        }

        $zwischenspeicher = $dir . '/summe.json';
        if (is_file($zwischenspeicher) && time() - filemtime($zwischenspeicher) < self::ZAEHLUNG_GILT) {
            $wert = json_decode((string) @file_get_contents($zwischenspeicher), true);

            // Eine gespeicherte Null wird nicht geglaubt, sondern neu gezählt.
            // Sie kann nur aus einem Moment stammen, in dem noch nichts da war –
            // und blockiert sonst eine Stunde lang die richtige Zahl.
            if (is_int($wert) && $wert > 0) {
                return $wert;
            }
        }

        $gesehen = [];
        for ($i = 0; $i < self::FENSTER_TAGE; $i++) {
            $datei = $dir . '/' . gmdate('Y-m-d', time() - $i * 86400) . '.txt';
            if (!is_file($datei)) {
                continue;
            }
            foreach (explode("\n", (string) @file_get_contents($datei)) as $zeile) {
                $zeile = trim($zeile);
                if ($zeile !== '') {
                    $gesehen[$zeile] = true;
                }
            }
        }

        $summe = count($gesehen);

        // Eine Null wird bewusst nicht festgehalten. Sie entsteht, solange noch
        // kein Abruf vorliegt – etwa direkt nach einem Upload, bei dem der
        // Zwischenspeicher geleert wurde. Gälte sie eine Stunde, bliebe die
        // Zeile auch nach dem ersten echten Abruf verschwunden.
        if ($summe > 0) {
            @file_put_contents($zwischenspeicher, json_encode($summe), LOCK_EX);
        } elseif (is_file($zwischenspeicher)) {
            @unlink($zwischenspeicher);
        }

        return $summe;
    }

    /**
     * Soll die Zahl öffentlich gezeigt werden?
     *
     * Unterhalb der Schwelle bleibt sie verborgen: „3 aktive Kalender“ wirkt
     * schwächer als gar keine Angabe.
     */
    public static function shouldDisplay(): bool
    {
        return LH_STATS_ENABLED
            && LH_STATS_PUBLIC
            && self::activeCalendars() >= LH_STATS_MIN_DISPLAY;
    }

    /**
     * Stabile Kennung eines Kalenders aus seinen Parametern.
     *
     * Bewusst grob: Die Koordinaten werden auf zwei Nachkommastellen gerundet
     * (rund einen Kilometer). Das genügt, um Abos auseinanderzuhalten, macht
     * den Hash aber unbrauchbar, um einen Standort daraus zurückzugewinnen.
     *
     * @param string[] $events
     */
    public static function fingerprint(float $lat, float $lon, array $events, int $months, string $tz): string
    {
        sort($events);

        return sprintf(
            '%.2f|%.2f|%s|%d|%s',
            $lat,
            $lon,
            implode(',', $events),
            $months,
            $tz
        );
    }

    private static function dir(): ?string
    {
        $dir = (defined('LH_CACHE_DIR') ? LH_CACHE_DIR : '') . '/stats';
        if ($dir === '/stats') {
            return null;
        }
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
            \LightHours\verzeichnis_schuetzen(LH_CACHE_DIR);
        }

        return is_dir($dir) && is_writable($dir) ? $dir : null;
    }

    /** Alte Tagesdateien löschen – gelegentlich genügt */
    private static function aufraeumen(string $dir): void
    {
        if (random_int(1, 200) !== 1) {
            return;
        }

        $grenze = time() - self::AUFBEWAHRUNG_TAGE * 86400;
        foreach (glob($dir . '/*.txt') ?: [] as $datei) {
            if (filemtime($datei) < $grenze) {
                @unlink($datei);
            }
        }
    }
}
