<?php
/**
 * E-Mail-Versand ohne externe Bibliothek.
 *
 * Zwei Wege, in dieser Reihenfolge:
 *   1. SMTP – wenn in config.php ein Server hinterlegt ist. Zuverlässiger,
 *      weil die Nachricht von einem Server kommt, der für die Domain
 *      autorisiert ist (SPF/DKIM).
 *   2. mail() – der eingebaute Weg. Funktioniert überall, landet auf
 *      günstigem Webspace aber gern im Spam-Ordner.
 *
 * Kein Composer, kein PHPMailer: 150 Zeilen SMTP sind überschaubarer als eine
 * Abhängigkeit, die bei jedem Update gepflegt werden will.
 */

declare(strict_types=1);

namespace LightHours;

final class Mailer
{
    /**
     * Nachricht verschicken.
     *
     * @param  string $textBody  reine Textfassung (Pflicht)
     * @param  string $htmlBody  HTML-Fassung, leer = nur Text
     * @throws \RuntimeException wenn der Versand scheitert
     */
    public static function send(string $to, string $subject, string $textBody, string $htmlBody = ''): void
    {
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('Ungültige Empfängeradresse.');
        }

        $from     = LH_MAIL_FROM !== '' ? LH_MAIL_FROM : 'lighthours@' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
        $fromName = LH_MAIL_FROM_NAME !== '' ? LH_MAIL_FROM_NAME : 'lighthours';

        $grenze  = 'lh-' . bin2hex(random_bytes(12));
        $headers = [
            'From: ' . self::encodeName($fromName) . ' <' . $from . '>',
            'Reply-To: ' . $from,
            'MIME-Version: 1.0',
            'Date: ' . date(DATE_RFC2822),
            'Message-ID: <' . bin2hex(random_bytes(12)) . '@' . self::domain($from) . '>',
            'Auto-Submitted: auto-generated',
            'X-Mailer: lighthours',
        ];

        if ($htmlBody !== '') {
            $headers[] = 'Content-Type: multipart/alternative; boundary="' . $grenze . '"';
            $body = "--{$grenze}\r\n"
                  . "Content-Type: text/plain; charset=UTF-8\r\n"
                  . "Content-Transfer-Encoding: base64\r\n\r\n"
                  . chunk_split(base64_encode($textBody)) . "\r\n"
                  . "--{$grenze}\r\n"
                  . "Content-Type: text/html; charset=UTF-8\r\n"
                  . "Content-Transfer-Encoding: base64\r\n\r\n"
                  . chunk_split(base64_encode($htmlBody)) . "\r\n"
                  . "--{$grenze}--\r\n";
        } else {
            $headers[] = 'Content-Type: text/plain; charset=UTF-8';
            $headers[] = 'Content-Transfer-Encoding: base64';
            $body = chunk_split(base64_encode($textBody));
        }

        $betreff = self::encodeHeader($subject);

        if (LH_SMTP_HOST !== '') {
            self::viaSmtp($from, $to, $betreff, $headers, $body);

            return;
        }

        // Rückfallebene: eingebautes mail()
        $kopf = implode("\r\n", $headers);
        if (!@mail($to, $betreff, $body, $kopf, '-f' . $from)) {
            throw new \RuntimeException(
                'Der Versand über mail() ist fehlgeschlagen. Viele Hoster sperren die '
                . 'Funktion – dann in config.php einen SMTP-Zugang eintragen.'
            );
        }
    }

    /** Versand über einen SMTP-Server */
    private static function viaSmtp(string $from, string $to, string $subject, array $headers, string $body): void
    {
        $host    = LH_SMTP_HOST;
        $port    = LH_SMTP_PORT;
        $timeout = 15;

        // Bei Port 465 läuft die Verbindung von Anfang an verschlüsselt
        $ziel = LH_SMTP_SECURE === 'ssl' ? "ssl://{$host}:{$port}" : "{$host}:{$port}";

        $verbindung = @stream_socket_client($ziel, $fehlerNr, $fehlerText, $timeout);
        if ($verbindung === false) {
            throw new \RuntimeException("SMTP-Verbindung zu {$host}:{$port} fehlgeschlagen: {$fehlerText}");
        }
        stream_set_timeout($verbindung, $timeout);

        try {
            self::erwarte($verbindung, 220);
            self::sende($verbindung, 'EHLO ' . self::domain($from), 250);

            if (LH_SMTP_SECURE === 'tls') {
                self::sende($verbindung, 'STARTTLS', 220);
                if (!@stream_socket_enable_crypto($verbindung, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new \RuntimeException('TLS-Verschlüsselung konnte nicht aufgebaut werden.');
                }
                self::sende($verbindung, 'EHLO ' . self::domain($from), 250);
            }

            if (LH_SMTP_USER !== '') {
                self::sende($verbindung, 'AUTH LOGIN', 334);
                self::sende($verbindung, base64_encode(LH_SMTP_USER), 334);
                self::sende($verbindung, base64_encode(LH_SMTP_PASS), 235);
            }

            self::sende($verbindung, 'MAIL FROM:<' . $from . '>', 250);
            self::sende($verbindung, 'RCPT TO:<' . $to . '>', 250);
            self::sende($verbindung, 'DATA', 354);

            $nachricht = 'To: ' . $to . "\r\n"
                       . 'Subject: ' . $subject . "\r\n"
                       . implode("\r\n", $headers) . "\r\n\r\n"
                       . $body;

            // Zeilen, die mit einem Punkt beginnen, müssen verdoppelt werden,
            // sonst gilt die Nachricht dort als beendet.
            $nachricht = preg_replace('/^\./m', '..', $nachricht);

            fwrite($verbindung, $nachricht . "\r\n.\r\n");
            self::erwarte($verbindung, 250);

            self::sende($verbindung, 'QUIT', 221);
        } finally {
            fclose($verbindung);
        }
    }

    /** Befehl senden und Antwortcode prüfen */
    private static function sende($verbindung, string $befehl, int $erwartet): void
    {
        fwrite($verbindung, $befehl . "\r\n");
        self::erwarte($verbindung, $erwartet);
    }

    /** Auf einen bestimmten Antwortcode warten */
    private static function erwarte($verbindung, int $code): void
    {
        $antwort = '';
        while (($zeile = fgets($verbindung, 515)) !== false) {
            $antwort .= $zeile;
            // Mehrzeilige Antworten haben an vierter Stelle einen Bindestrich
            if (strlen($zeile) < 4 || $zeile[3] !== '-') {
                break;
            }
        }

        if ((int) substr($antwort, 0, 3) !== $code) {
            throw new \RuntimeException('SMTP-Server antwortete unerwartet: ' . trim($antwort));
        }
    }

    /** Domain aus einer Adresse ziehen */
    private static function domain(string $adresse): string
    {
        $teile = explode('@', $adresse);

        return $teile[1] ?? ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }

    /** Kopfzeile mit Sonderzeichen nach RFC 2047 kodieren */
    private static function encodeHeader(string $text): string
    {
        if (preg_match('/^[\x20-\x7E]*$/', $text)) {
            return $text;
        }

        return '=?UTF-8?B?' . base64_encode($text) . '?=';
    }

    /** Anzeigename kodieren und in Anführungszeichen setzen, falls nötig */
    private static function encodeName(string $name): string
    {
        $kodiert = self::encodeHeader($name);

        return $kodiert === $name ? '"' . addslashes($name) . '"' : $kodiert;
    }
}
