<?php
/**
 * Einfache Missbrauchsbremse für den E-Mail-Versand.
 *
 * Ohne Bremse wäre das Formular ein offener Versandapparat: Jeder könnte darüber
 * beliebige Adressen mit Nachrichten von deiner Domain zuschütten – und deine
 * Domain landet auf Sperrlisten.
 *
 * Gespeichert wird ausschließlich ein gekürzter Hash der IP-Adresse mit
 * Zeitstempel. Aus dem Hash lässt sich die Adresse nicht zurückrechnen, und
 * nach Ablauf des Zeitfensters wird der Eintrag gelöscht.
 */

declare(strict_types=1);

namespace LightHours;

final class RateLimit
{
    /**
     * Prüfen und zugleich zählen.
     *
     * @param  string $bereich Name des Kontingents, z. B. 'mail'
     * @param  int    $max     erlaubte Vorgänge im Zeitfenster
     * @param  int    $fenster Zeitfenster in Sekunden
     * @return bool   true = erlaubt, false = Kontingent erschöpft
     */
    public static function allow(string $bereich, int $max, int $fenster): bool
    {
        $dir = self::dir();
        if ($dir === null) {
            // Ohne beschreibbares Verzeichnis lässt sich nicht zählen. Dann
            // lieber ablehnen als einen offenen Versandapparat betreiben.
            return false;
        }

        self::aufraeumen($dir, $fenster);

        $datei = $dir . '/' . $bereich . '_' . self::kennung() . '.txt';
        $jetzt = time();

        $zeiten = is_file($datei)
            ? array_map('intval', array_filter(explode("\n", (string) @file_get_contents($datei))))
            : [];

        // Nur Einträge im aktuellen Zeitfenster zählen
        $zeiten = array_values(array_filter($zeiten, static fn(int $t): bool => $t > $jetzt - $fenster));

        if (count($zeiten) >= $max) {
            return false;
        }

        $zeiten[] = $jetzt;
        @file_put_contents($datei, implode("\n", $zeiten), LOCK_EX);

        return true;
    }

    /** Gekürzter Hash der IP-Adresse, mit Tagesschlüssel gesalzen */
    private static function kennung(): string
    {
        $ip   = (string) ($_SERVER['REMOTE_ADDR'] ?? 'unbekannt');
        $salz = date('Y-m-d') . '|lighthours';

        return substr(hash('sha256', $ip . $salz), 0, 24);
    }

    private static function dir(): ?string
    {
        $dir = (defined('LH_CACHE_DIR') ? LH_CACHE_DIR : '') . '/limits';
        if ($dir === '/limits') {
            return null;
        }
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        return is_dir($dir) && is_writable($dir) ? $dir : null;
    }

    /** Abgelaufene Einträge löschen – gelegentlich genügt */
    private static function aufraeumen(string $dir, int $fenster): void
    {
        if (random_int(1, 20) !== 1) {
            return;
        }

        foreach (glob($dir . '/*.txt') ?: [] as $datei) {
            if (filemtime($datei) < time() - max($fenster, 3600) * 2) {
                @unlink($datei);
            }
        }
    }
}
