<?php
/**
 * Sehr einfacher Dateizwischenspeicher.
 *
 * Bewusst ohne Datenbank: lighthours soll auf reinem FTP-Webspace laufen.
 * Fehlt das Verzeichnis oder ist es nicht beschreibbar, arbeitet die Anwendung
 * einfach ohne Zwischenspeicher weiter.
 */

declare(strict_types=1);

namespace LightHours;

final class Cache
{
    /**
     * Fassung des Speicherformats.
     *
     * Bei jeder Änderung erhöhen, die den Inhalt zwischengespeicherter Einträge
     * betrifft – etwa an der Zeitzonenermittlung oder an der Beschriftung von
     * Treffern. Alte Einträge werden dadurch schlicht nicht mehr gefunden und
     * nach und nach neu aufgebaut. Ohne das wirkt eine Korrektur erst, wenn der
     * letzte alte Eintrag abgelaufen ist – im Zweifel eine Woche später.
     */
    private const VERSION = 2;

    private static ?bool $usable = null;

    private static function dir(): ?string
    {
        if (self::$usable === false) {
            return null;
        }

        $dir = defined('LH_CACHE_DIR') ? LH_CACHE_DIR : '';
        if ($dir === '') {
            self::$usable = false;

            return null;
        }

        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
            \LightHours\verzeichnis_schuetzen(LH_CACHE_DIR);
        }

        self::$usable = is_dir($dir) && is_writable($dir);

        return self::$usable ? $dir : null;
    }

    public static function get(string $key): mixed
    {
        $dir = self::dir();
        if ($dir === null) {
            return null;
        }

        $file = self::path($dir, $key);
        if (!is_file($file)) {
            return null;
        }

        if (time() - filemtime($file) > LH_CACHE_TTL) {
            @unlink($file);

            return null;
        }

        $data = json_decode((string) @file_get_contents($file), true);

        return $data === null ? null : $data;
    }

    public static function set(string $key, mixed $value): void
    {
        $dir = self::dir();
        if ($dir === null) {
            return;
        }

        @file_put_contents(self::path($dir, $key), json_encode($value, JSON_UNESCAPED_UNICODE), LOCK_EX);
    }

    /** Dateiname eines Eintrags, einschließlich der Formatfassung */
    private static function path(string $dir, string $key): string
    {
        return $dir . '/v' . self::VERSION . '_' . preg_replace('/[^a-z0-9_]/i', '', $key) . '.json';
    }
}
