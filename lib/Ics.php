<?php
/**
 * iCalendar-Erzeugung (RFC 5545).
 *
 * Erzeugt Termine mit echter Zeitzonenangabe (TZID) samt passender
 * VTIMEZONE-Komponente. Dadurch zeigen Kalender-Apps die Lichtzeiten immer in
 * der Ortszeit des gewählten Orts – auch wenn der Nutzer selbst woanders sitzt.
 */

declare(strict_types=1);

namespace LightHours;

final class Ics
{
    private const EMOJI = [
        'golden_morning' => "\u{1F304}",
        'golden_evening' => "\u{1F307}",
        'blue_morning'   => "\u{1F30C}",
        'blue_evening'   => "\u{1F306}",
    ];

    /**
     * Kompletten Kalender bauen.
     *
     * @param string[]      $events          gewünschte Phasen
     * @param int|null      $reminderMinutes Vorlauf der Erinnerung, null = keine
     */
    public static function build(
        float $lat,
        float $lon,
        \DateTimeImmutable $from,
        \DateTimeImmutable $to,
        array $events,
        \DateTimeZone $tz,
        I18n $i18n,
        ?int $reminderMinutes = null,
        string $locationName = ''
    ): string {
        $name  = $locationName !== '' ? $locationName : sprintf('%.4f, %.4f', $lat, $lon);
        $utc   = new \DateTimeZone('UTC');
        $stamp = (new \DateTimeImmutable('now', $utc))->format('Ymd\THis\Z');

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//lighthours//lighthours ' . LH_VERSION . '//' . strtoupper($i18n->lang()),
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'X-WR-CALNAME:' . self::esc($i18n->t('cal.name', ['name' => $name])),
            'X-WR-CALDESC:' . self::esc($i18n->t('cal.description', ['name' => $name])),
            'X-WR-TIMEZONE:' . $tz->getName(),
            'REFRESH-INTERVAL;VALUE=DURATION:P1D',
            'X-PUBLISHED-TTL:P1D',
        ];

        foreach (self::vtimezone($tz, $from, $to) as $line) {
            $lines[] = $line;
        }

        foreach (LightPhases::forRange($from, $to, $lat, $lon, $events, $tz) as $phase) {
            $start = (new \DateTimeImmutable('@' . $phase['start']))->setTimezone($tz);
            $end   = (new \DateTimeImmutable('@' . $phase['end']))->setTimezone($tz);
            $title = $i18n->t('event.' . $phase['event']);
            $tzid  = $tz->getName();

            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:' . self::uid($phase, $lat, $lon);
            $lines[] = 'DTSTAMP:' . $stamp;
            $lines[] = "DTSTART;TZID={$tzid}:" . $start->format('Ymd\THis');
            $lines[] = "DTEND;TZID={$tzid}:" . $end->format('Ymd\THis');
            $lines[] = 'SUMMARY:' . self::esc(self::EMOJI[$phase['event']] . ' ' . $title);
            $lines[] = 'LOCATION:' . self::esc($name);
            $lines[] = 'GEO:' . sprintf('%.6f;%.6f', $lat, $lon);
            $lines[] = 'TRANSP:TRANSPARENT';
            // Sonnenauf- oder -untergang als Bezugspunkt. Die Goldene Stunde
            // endet abends erst 25 Minuten nach dem Untergang – ohne diese
            // Zeile wirkt der Termin schlicht falsch.
            $bezug = '';
            if (isset($phase['horizon']) && $phase['horizon'] !== null) {
                $zeit = (new \DateTimeImmutable('@' . $phase['horizon']))
                    ->setTimezone($tz)->format('H:i');
                $bezug = $i18n->t(
                    ($phase['rising'] ?? false) ? 'cal.sunrise' : 'cal.sunset',
                    ['time' => $zeit]
                );
            }

            $lines[] = 'DESCRIPTION:' . self::esc($i18n->t('cal.event_description', [
                'event' => $title,
                'name'  => $name,
                'start' => $start->format('H:i'),
                'end'   => $end->format('H:i'),
                'sun'   => $bezug,
            ]));

            if ($reminderMinutes !== null && $reminderMinutes > 0) {
                $lines[] = 'BEGIN:VALARM';
                $lines[] = 'ACTION:DISPLAY';
                $lines[] = 'DESCRIPTION:' . self::esc(self::EMOJI[$phase['event']] . ' ' . $title);
                $lines[] = 'TRIGGER:-PT' . $reminderMinutes . 'M';
                $lines[] = 'END:VALARM';
            }

            $lines[] = 'END:VEVENT';
        }

        $lines[] = 'END:VCALENDAR';

        return implode("\r\n", array_map([self::class, 'fold'], $lines)) . "\r\n";
    }

    /**
     * VTIMEZONE-Komponente aus der PHP-Zeitzonendatenbank.
     *
     * Es werden alle Umstellungen im abgedeckten Zeitraum als eigene
     * Beobachtungen ausgegeben – das ist robuster als aus Regeln erzeugte
     * RRULEs und wird von allen gängigen Kalendern verstanden.
     *
     * @return string[]
     */
    private static function vtimezone(
        \DateTimeZone $tz,
        \DateTimeImmutable $from,
        \DateTimeImmutable $to
    ): array {
        $start = $from->modify('-1 year')->getTimestamp();
        $end   = $to->modify('+1 year')->getTimestamp();

        $transitions = $tz->getTransitions($start, $end);
        $lines       = ['BEGIN:VTIMEZONE', 'TZID:' . $tz->getName()];

        if (count($transitions) === 0) {
            // Sollte nicht vorkommen; Rückfallebene: feste UTC-Zone
            $lines[] = 'BEGIN:STANDARD';
            $lines[] = 'DTSTART:19700101T000000';
            $lines[] = 'TZOFFSETFROM:+0000';
            $lines[] = 'TZOFFSETTO:+0000';
            $lines[] = 'END:STANDARD';
            $lines[] = 'END:VTIMEZONE';

            return $lines;
        }

        $prevOffset = $transitions[0]['offset'];

        foreach ($transitions as $i => $tr) {
            $type = $tr['isdst'] ? 'DAYLIGHT' : 'STANDARD';

            // Ortszeit des Umstellungszeitpunkts, ausgedrückt im neuen Versatz
            $local = (new \DateTimeImmutable('@' . $tr['ts']))
                ->setTimezone(new \DateTimeZone('UTC'))
                ->modify(($tr['offset'] >= 0 ? '+' : '-') . abs($tr['offset']) . ' seconds');

            $dtstart = $i === 0
                ? (new \DateTimeImmutable('@' . $start))->format('Ymd\THis')
                : $local->format('Ymd\THis');

            $lines[] = 'BEGIN:' . $type;
            $lines[] = 'DTSTART:' . $dtstart;
            $lines[] = 'TZOFFSETFROM:' . self::offset($i === 0 ? $tr['offset'] : $prevOffset);
            $lines[] = 'TZOFFSETTO:' . self::offset($tr['offset']);
            if (!empty($tr['abbr'])) {
                $lines[] = 'TZNAME:' . self::esc((string) $tr['abbr']);
            }
            $lines[] = 'END:' . $type;

            $prevOffset = $tr['offset'];
        }

        $lines[] = 'END:VTIMEZONE';

        return $lines;
    }

    /** Sekunden-Versatz → ±HHMM */
    private static function offset(int $seconds): string
    {
        $sign = $seconds < 0 ? '-' : '+';
        $abs  = abs($seconds);

        return sprintf('%s%02d%02d', $sign, intdiv($abs, 3600), intdiv($abs % 3600, 60));
    }

    /** Stabile, reproduzierbare UID pro Termin */
    private static function uid(array $phase, float $lat, float $lon): string
    {
        $raw = sprintf('%s|%d|%.4f|%.4f', $phase['event'], $phase['start'], $lat, $lon);

        return substr(sha1($raw), 0, 20) . '@lighthours';
    }

    /** Sonderzeichen nach RFC 5545 maskieren */
    private static function esc(string $text): string
    {
        return str_replace(
            ["\\", "\r\n", "\n", "\r", ';', ','],
            ['\\\\', '\\n', '\\n', '\\n', '\\;', '\\,'],
            $text
        );
    }

    /**
     * Zeilen auf 75 Oktette umbrechen (RFC 5545).
     *
     * Der Umbruch respektiert UTF-8-Grenzen – sonst zerbrechen Umlaute und
     * Emojis und der Kalender wird unlesbar.
     */
    private static function fold(string $line): string
    {
        if (strlen($line) <= 75) {
            return $line;
        }

        $out   = '';
        $chunk = '';
        $limit = 75;

        foreach (preg_split('//u', $line, -1, PREG_SPLIT_NO_EMPTY) as $char) {
            if (strlen($chunk) + strlen($char) > $limit) {
                $out  .= $chunk . "\r\n ";
                $chunk = '';
                $limit = 74; // Folgezeilen beginnen mit einem Leerzeichen
            }
            $chunk .= $char;
        }

        return $out . $chunk;
    }
}
