<?php
/**
 * Mehrsprachigkeit.
 *
 * Eine PHP-Datei je Sprache in /lang. Neue Sprache = neue Datei, sonst nichts.
 * Bewusst ohne gettext: das ist auf günstigem Webhosting oft nicht verfügbar.
 */

declare(strict_types=1);

namespace LightHours;

final class I18n
{
    public const DEFAULT_LANG = 'de';

    /** @var array<string,string> */
    private array $catalog;

    /** @var array<string,string> */
    private array $fallback;

    public function __construct(private string $lang)
    {
        if (!in_array($lang, self::available(), true)) {
            $this->lang = self::DEFAULT_LANG;
        }

        $this->catalog  = require __DIR__ . '/../lang/' . $this->lang . '.php';
        $this->fallback = $this->lang === 'en'
            ? $this->catalog
            : require __DIR__ . '/../lang/en.php';
    }

    /** Verfügbare Sprachen (aus den vorhandenen Dateien) */
    public static function available(): array
    {
        static $langs = null;
        if ($langs === null) {
            $langs = array_map(
                static fn(string $f): string => basename($f, '.php'),
                glob(__DIR__ . '/../lang/*.php') ?: []
            );
            sort($langs);
        }

        return $langs;
    }

    /**
     * Eigenbezeichnung einer Sprache, etwa 'Italiano' für 'it'.
     *
     * Sprachen nennen sich in Auswahllisten üblicherweise selbst – wer die
     * Oberfläche nicht versteht, erkennt „Español“ verlässlicher als „Spanisch“.
     * Fehlt der Eintrag, bleibt der Code als Notbehelf.
     */
    public static function nativeName(string $code): string
    {
        static $cache = [];

        if (!isset($cache[$code])) {
            $datei = __DIR__ . '/../lang/' . basename($code) . '.php';
            $texte = is_file($datei) ? require $datei : [];
            $cache[$code] = (string) ($texte['lang.name'] ?? strtoupper($code));
        }

        return $cache[$code];
    }

    /** Sprache aus URL-Segment, Cookie oder Browser-Header bestimmen */
    public static function detect(?string $fromUrl = null): string
    {
        $available = self::available();

        if ($fromUrl !== null && in_array($fromUrl, $available, true)) {
            return $fromUrl;
        }

        $header = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        foreach (explode(',', $header) as $part) {
            $code = strtolower(substr(trim(explode(';', $part)[0]), 0, 2));
            if (in_array($code, $available, true)) {
                return $code;
            }
        }

        return self::DEFAULT_LANG;
    }

    public function lang(): string
    {
        return $this->lang;
    }

    /**
     * Übersetzung mit optionalen Platzhaltern: {name}, {minutes} …
     *
     * @param array<string,string|int> $vars
     */
    public function t(string $key, array $vars = []): string
    {
        $text = $this->catalog[$key] ?? $this->fallback[$key] ?? $key;

        foreach ($vars as $k => $v) {
            $text = str_replace('{' . $k . '}', (string) $v, $text);
        }

        return $text;
    }

    /** Übersetzung, direkt HTML-sicher ausgegeben */
    public function e(string $key, array $vars = []): string
    {
        return htmlspecialchars($this->t($key, $vars), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
