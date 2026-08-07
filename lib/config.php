<?php
/**
 * Konfiguration von lighthours.
 *
 * Für den Normalbetrieb muss hier nichts geändert werden – bis auf die
 * Kontaktadresse in LH_USER_AGENT. Bitte eintragen: Nominatim verlangt eine
 * erreichbare Kontaktadresse und sperrt anonyme Zugriffe.
 */

declare(strict_types=1);

/** Fassung der Anwendung. Erscheint in der Kennung des Kalenders. */
const LH_VERSION = '1.1.0';

/** Kontaktadresse für die Ortssuche (Pflicht laut Nominatim-Nutzungsbedingungen) */
const LH_USER_AGENT = 'lighthours/' . LH_VERSION . ' (+https://example.org; kontakt@example.org)';

/**
 * Basis-URL der Installation, ohne Schrägstrich am Ende.
 *
 * Fest eingetragen, weil davon die Abo-Links, die kanonischen Adressen und die
 * Vorschaubilder für soziale Netzwerke abhängen. Leer lassen ermittelt sie aus
 * der Anfrage – das genügt im Betrieb, geht aber schief, sobald die Seite über
 * mehrere Adressen erreichbar ist.
 */
const LH_BASE_URL = '';

/**
 * Name des Betreibers. Erscheint in den strukturierten Daten für Suchmaschinen.
 * Leer lassen lässt die Angabe weg.
 */
const LH_AUTHOR = '';

/**
 * Adresse des Quellcodes, erscheint als Verweis in der Fußzeile.
 * Leer lassen blendet den Verweis aus – etwa vor der Veröffentlichung.
 */
const LH_SOURCE_URL = 'https://github.com/3pixelhoch/lighthours';

/** Endpunkt der Ortssuche. Bei eigener Nominatim-Instanz hier umstellen. */
const LH_NOMINATIM_URL = 'https://nominatim.openstreetmap.org';

/** Standardsprache, wenn der Browser nichts Passendes meldet */
const LH_DEFAULT_LANG = 'de';

/** Maximaler Zeitraum eines Kalenders in Monaten */
const LH_MAX_MONTHS = 60;

/**
 * Verzeichnis für den Zwischenspeicher der Ortssuche.
 * Leer lassen = Zwischenspeicher aus. Entlastet Nominatim spürbar.
 */
const LH_CACHE_DIR = __DIR__ . '/../cache';

/** Wie lange Suchergebnisse zwischengespeichert werden (Sekunden) */
const LH_CACHE_TTL = 604800; // 7 Tage

/**
 * Buy-me-a-coffee-Benutzername, z. B. 'deinname'.
 * Leer lassen blendet Button und Fußzeilen-Link vollständig aus.
 *
 * Bewusst als eigener Button statt als offizielles BMC-Skript: Das Skript wird
 * von einem fremden CDN geladen und würde die Datenschutzaussage der Seite
 * aushebeln. Hier entsteht nur ein normaler Link – kein fremder Code, kein
 * Aufruf, solange niemand klickt.
 */
const LH_COFFEE_USER = '';


/* ------------------------------------------------------------------ E-Mail */

/**
 * Freiwilliger Versand des Kalenderlinks per E-Mail.
 * false = das Formular erscheint gar nicht erst.
 */
const LH_MAIL_ENABLED = false;

/** Absenderadresse. Muss zur Domain passen, sonst landet alles im Spam. */
const LH_MAIL_FROM      = '';
const LH_MAIL_FROM_NAME = 'lighthours';

/** Höchstzahl Nachrichten je Stunde und Besucher – Schutz vor Missbrauch */
const LH_MAIL_MAX_PER_HOUR = 5;

/**
 * SMTP-Zugang. Leerer Host = Versand über die eingebaute mail()-Funktion.
 *
 * Auf günstigem Webspace ist SMTP fast immer die bessere Wahl: mail() wird
 * häufig gesperrt oder die Nachrichten landen im Spam-Ordner, weil der Server
 * für die Absenderdomain nicht autorisiert ist.
 *
 * LH_SMTP_SECURE: 'tls' (Port 587, üblich), 'ssl' (Port 465) oder '' (ohne)
 */
const LH_SMTP_HOST   = '';
const LH_SMTP_PORT   = 587;
const LH_SMTP_SECURE = 'tls';
const LH_SMTP_USER   = '';
const LH_SMTP_PASS   = '';

/* ------------------------------------------------------- Anonyme Zählung */

/**
 * Zählt, wie viele verschiedene Kalender in den letzten 30 Tagen abgerufen
 * wurden. Gespeichert wird nur ein gekürzter Hash der Kalenderparameter –
 * keine IP-Adressen, keine Browserkennungen, keine Uhrzeiten.
 *
 * false = es wird gar nichts gezählt und nichts geschrieben.
 */
const LH_STATS_ENABLED = true;

/** Zahl in der Fußzeile anzeigen. false = nur intern zählen. */
const LH_STATS_PUBLIC = true;

/**
 * Erst ab dieser Zahl wird sie öffentlich gezeigt.
 *
 * Auf 1 gesetzt, damit die Angabe von Beginn an sichtbar ist. Wer eine kleine
 * Zahl am Anfang lieber verbirgt, setzt hier etwa 25 – dann erscheint die
 * Zeile erst, wenn sie beeindruckt.
 */
const LH_STATS_MIN_DISPLAY = 1;
