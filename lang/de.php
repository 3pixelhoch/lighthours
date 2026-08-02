<?php
/**
 * Deutsche Texte.
 *
 * Neue Sprache anlegen: diese Datei kopieren, Werte übersetzen, als
 * <sprachcode>.php speichern. Mehr ist nicht nötig – die Sprache erscheint
 * dann automatisch in der Umschaltung.
 */

declare(strict_types=1);

return [
    // Kalender
    'event.golden_morning' => 'Goldene Stunde (Morgen)',
    'event.golden_evening' => 'Goldene Stunde (Abend)',
    'event.blue_morning'   => 'Blaue Stunde (Morgen)',
    'event.blue_evening'   => 'Blaue Stunde (Abend)',
    'cal.name'             => 'lighthours – {name}',
    'cal.description'      => 'Goldene und Blaue Stunde für {name}. Erstellt mit lighthours.',
    'cal.event_description'=> "{event} in {name}\nBeginn: {start} Uhr\nEnde: {end} Uhr\n\nErstellt mit lighthours – Lichtplanung für Fotografie und Film.",

    // Seitenkopf
    'meta.title'       => 'lighthours – Goldene & Blaue Stunde als Kalender-Abo',
    'meta.description' => 'Kostenloser Kalender mit den täglichen Zeiten der Goldenen und Blauen Stunde für jeden Ort der Welt. Abonnierbar in Apple Kalender, Google Kalender und Outlook. Open Source.',
    'nav.skip'         => 'Zum Inhalt springen',

    // Startseite
    'hero.tagline'  => 'Das beste Licht.<br>Direkt in deinem Kalender.',
    'hero.intro'    => 'lighthours berechnet die täglichen Zeiten der Goldenen und Blauen Stunde für jeden Ort der Welt – und liefert sie als Kalender, den du einmal abonnierst und nie wieder anfassen musst.',
    'hero.cta'      => 'Kalender erstellen',
    'hero.badge'    => 'Kostenlos · Ohne Konto · Open Source',

    'why.title'     => 'Warum diese Stunden zählen',
    'why.golden'    => 'Goldene Stunde',
    'why.golden_t'  => 'Kurz nach Sonnenaufgang und vor Sonnenuntergang steht die Sonne tief. Das Licht wird weich, warm und gerichtet – Hauttöne schmeicheln, Landschaften bekommen Tiefe, harte Schatten verschwinden.',
    'why.blue'      => 'Blaue Stunde',
    'why.blue_t'    => 'Davor und danach liegt die Dämmerung: Der Himmel leuchtet tiefblau, künstliches Licht und Restlicht halten sich die Waage. Die Zeit für Stadt, Architektur und Stimmung.',
    'why.short'     => 'Kurz',
    'why.short_t'   => 'Beides zusammen dauert selten länger als eine Stunde und verschiebt sich täglich. Wer es im Kalender stehen hat, plant Shootings realistisch – statt am Set festzustellen, dass das Licht schon weg ist.',

    'how.title'   => 'So funktioniert es',
    'how.step1'   => 'Ort eingeben',
    'how.step1_t' => 'Stadt, Adresse oder Postleitzahl genügt. Koordinaten musst du nie selbst heraussuchen.',
    'how.step2'   => 'Optionen wählen',
    'how.step2_t' => 'Welche Lichtphasen, welcher Zeitraum, welche Erinnerung. Auf der Karte kannst du den Mittelpunkt feinjustieren.',
    'how.step3'   => 'Abonnieren',
    'how.step3_t' => 'Ein Klick, und die Termine stehen in deinem Kalender. Bei einem Abo aktualisieren sie sich von allein.',

    'free.title' => 'Warum kostenlos?',
    'free.text'  => 'Die Berechnung kostet nichts außer ein wenig Rechenzeit, und Sonnenstände gehören niemandem. Es gibt keine Konten, keine Werbung, kein Tracking und keine Zahlungsschranke – auch nicht später.',
    'os.title'   => 'Warum Open Source?',
    'os.text'    => 'Der komplette Quellcode ist einsehbar und darf frei verwendet werden. Wer will, betreibt lighthours auf dem eigenen Webspace. Kein Dienst, der irgendwann abgeschaltet wird und deine Kalender mitnimmt.',
    'privacy.title' => 'Was ist mit Daten?',
    'privacy.text'  => 'Keine Cookies, keine Tracker, keine gespeicherten Besuche. Einzig deine Wahl des Farbmodus merkt sich der Browser lokal. Schriften liegen auf dem eigenen Server, die Ortssuche läuft über das Backend – deine Suchbegriffe erreichen OpenStreetMap also ohne deine IP-Adresse. Einzige Ausnahme: Die Kartenkacheln lädt dein Browser direkt von openstreetmap.org, sobald du einen Ort ausgewählt hast. Wie viele Kalender gerade aktiv sind, wird anonym gezählt: gespeichert wird allein ein Hash der Kalendereinstellungen, ohne IP-Adresse und ohne Zeitpunkt.',

    // Generator
    'gen.title'    => 'Kalender erstellen',
    'gen.subtitle' => 'In drei Schritten fertig – ohne Konto, ohne E-Mail-Adresse.',

    'gen.step_place'   => 'Ort',
    'gen.search_label' => 'Stadt, Adresse oder Postleitzahl',
    'gen.search_ph'    => 'z. B. Hamburg, 20095 oder Elbstrand',
    'gen.search_btn'   => 'Suchen',
    'gen.searching'    => 'Suche läuft …',
    'gen.no_results'   => 'Dazu wurde nichts gefunden. Versuch es mit einer größeren Stadt in der Nähe.',
    'gen.geo_error'    => 'Die Ortssuche antwortet gerade nicht. Bitte in einem Moment noch einmal versuchen.',

    'gen.step_area'    => 'Bereich',
    'gen.map_hint'     => 'Marker verschieben oder auf die Karte klicken, um den Mittelpunkt anzupassen.',
    'gen.radius_label' => 'Radius',
    'gen.radius_custom'=> 'Eigener Radius',
    'gen.radius_info'  => 'In diesem Umkreis weichen die Lichtzeiten um höchstens etwa <strong>{minutes} Minuten</strong> ab.',
    'gen.radius_why'   => 'Deshalb brauchst du keinen eigenen Kalender pro Location: Ein Kalender deckt deine ganze Region ab.',
    'gen.timezone'     => 'Zeitzone',
    'gen.timezone_hint'=> 'Die Termine erscheinen in der Ortszeit dieses Orts.',

    'gen.step_options' => 'Optionen',
    'gen.events_label' => 'Welche Termine?',
    'gen.period_label' => 'Zeitraum',
    'gen.period_3'     => '3 Monate',
    'gen.period_6'     => '6 Monate',
    'gen.period_12'    => '1 Jahr',
    'gen.period_24'    => '2 Jahre',
    'gen.period_36'    => '3 Jahre',
    'gen.period_60'    => '5 Jahre',
    'gen.period_custom'=> 'Eigenes Enddatum',
    'gen.rolling'      => 'Rollierendes Abo',
    'gen.rolling_hint' => 'Der Kalender wandert mit: Es sind immer die kommenden {months} im Kalender, ohne dass du etwas tun musst.',
    'gen.lang_label'   => 'Sprache der Termine',
    'gen.reminder'     => 'Erinnerung',
    'gen.reminder_none'=> 'Keine',
    'gen.reminder_15'  => '15 Minuten vorher',
    'gen.reminder_30'  => '30 Minuten vorher',
    'gen.reminder_60'  => '60 Minuten vorher',

    'gen.preview'      => 'Vorschau',
    'gen.preview_hint' => 'Die nächsten Termine an diesem Ort:',
    'gen.today'        => 'Heute',
    'gen.tomorrow'     => 'Morgen',

    'gen.subscribe'    => 'Kalender abonnieren',
    'gen.subscribe_hint'=> 'Öffnet deine Kalender-App. Termine aktualisieren sich danach von allein.',
    'gen.download'     => 'ICS herunterladen',
    'gen.download_hint'=> 'Einmalige Datei zum Importieren – ohne Aktualisierung.',
    'gen.link_label'   => 'Oder Abo-Link kopieren',
    'gen.copy'         => 'Kopieren',
    'gen.copied'       => 'Kopiert',

    'gen.help_title'   => 'Abo einrichten',
    'gen.help_apple'   => '<strong>Apple Kalender:</strong> Auf „Kalender abonnieren“ tippen – der Rest passiert automatisch.',
    'gen.help_google'  => '<strong>Google Kalender:</strong> Link kopieren, dann in den Einstellungen unter „Kalender hinzufügen → Per URL“ einfügen.',
    'gen.help_outlook' => '<strong>Outlook:</strong> Link kopieren, dann „Kalender hinzufügen → Aus dem Internet abonnieren“.',

    // Fußzeile
    'footer.tagline' => 'Lichtplanung für Fotografie und Film.',
    'footer.source'  => 'Quellcode',
    'footer.api'     => 'API',
    'footer.privacy' => 'Datenschutz',
    'footer.data'    => 'Ortsdaten von OpenStreetMap',
    'footer.free'    => 'Frei nutzbar unter der MIT-Lizenz.',

    // Farbmodus
    'theme.auto' => 'Modus: System',
    'theme.light' => 'Modus: Hell',
    'theme.dark' => 'Modus: Dunkel',

    // Einrichtung
    'setup.title' => 'Einrichtung noch nicht abgeschlossen',
    'setup.text' => 'In <code>lib/config.php</code> steht bei <code>LH_USER_AGENT</code> noch die Platzhalteradresse. OpenStreetMap lehnt Anfragen damit ab – die Ortssuche kann deshalb nicht arbeiten. Trag dort eine echte Kontaktadresse ein, dann läuft es sofort.',
    'setup.check' => 'Ausführliche Prüfung öffnen',

    // Navigation, Orte, E-Mail, Unterstützung
    'nav.language' => 'Sprache',
    'nav.footer' => 'Weitere Seiten',
    'nav.generator' => 'Kalender',
    'mail.title' => 'Link per E-Mail schicken',
    'mail.hint' => 'Freiwillig. Die Adresse wird nur für diese eine Nachricht verwendet und nirgends gespeichert.',
    'mail.placeholder' => 'deine@adresse.de',
    'mail.send' => 'Senden',
    'mail.sent' => 'Verschickt – schau in dein Postfach.',
    'mail.failed' => 'Der Versand hat nicht geklappt. Versuch es später noch einmal oder kopier den Link.',
    'mail.invalid' => 'Diese E-Mail-Adresse sieht nicht richtig aus.',
    'mail.too_many' => 'Zu viele Nachrichten in kurzer Zeit. Bitte später erneut versuchen.',
    'mail.your_place' => 'deinem Ort',
    'mail.subject' => 'Dein lighthours-Kalender für {name}',
    'mail.body_intro' => "Hier ist dein persönlicher Kalender für {name}.\n\nEinmal abonniert, aktualisiert er sich von allein – du musst nie wieder daran denken.",
    'mail.link_label' => 'Falls der Knopf nicht funktioniert, hier der Link zum Kopieren:',
    'mail.footer' => 'Diese Nachricht wurde einmalig auf deine Anfrage verschickt. Deine Adresse wurde nicht gespeichert und du bekommst keine weitere Post von uns.',
    'mail.body_text' => "Hier ist dein persönlicher lighthours-Kalender für {name}.\n\nKalender abonnieren:\n{webcal}\n\nOder diesen Link in deiner Kalender-App eintragen:\n{url}\n\nSo geht es:\n- Apple Kalender: Link öffnen, der Rest passiert automatisch.\n- Google Kalender: Einstellungen, dann Kalender hinzufügen, dann Per URL.\n- Outlook: Kalender hinzufügen, dann Aus dem Internet abonnieren.\n\nDiese Nachricht wurde einmalig auf deine Anfrage verschickt. Deine Adresse wurde nicht gespeichert.",
    'support.title' => 'Gefällt dir lighthours?',
    'support.text' => 'Das Projekt ist kostenlos und bleibt es. Es gibt keine Werbung, keine Konten und keine Zahlungsschranke. Wenn es dir hilft, kannst du einen Kaffee ausgeben – muss aber nicht sein.',
    'support.button' => 'Kaffee ausgeben',
    'support.note' => 'Führt zu Buy Me a Coffee. Kein Skript von dort ist eingebunden – erst dein Klick verlässt diese Seite.',

    // Selbstprüfung
    'check.title' => 'Selbstprüfung',
    'check.intro' => 'Diese Seite prüft, ob auf diesem Server alles bereitsteht. Wenn überall ein Haken steht, kannst du check.php löschen.',
    'check.php_version' => 'PHP-Version',
    'check.ext' => 'Erweiterung {name}',
    'check.ext_ok' => 'vorhanden',
    'check.ext_missing' => 'fehlt',
    'check.outgoing' => 'Ausgehende Verbindungen möglich',
    'check.via_curl' => 'über cURL',
    'check.via_fopen' => 'über allow_url_fopen',
    'check.via_none' => 'weder cURL noch allow_url_fopen',
    'check.contact' => 'Kontaktadresse eingetragen',
    'check.contact_missing' => 'noch die Platzhalteradresse',
    'check.calc' => 'Astronomische Berechnung',
    'check.calc_ok' => 'Goldene Stunde am 21.06. in Hamburg: {time} Uhr',
    'check.tzdb' => 'Zeitzonendatenbank',
    'check.geo' => 'Ortssuche (Test mit Postleitzahl 20095)',
    'check.geo_ok' => '{count} Treffer, erster: {first}',
    'check.geo_none' => 'kein Treffer',
    'check.geo_skipped' => 'übersprungen – erst die Kontaktadresse eintragen',
    'check.cache' => 'Zwischenspeicher beschreibbar',
    'check.yes' => 'ja',
    'check.cache_no' => 'nein – läuft auch ohne, nur langsamer',
    'check.help_php' => 'lighthours braucht PHP 8.1 oder neuer. Im Hosting-Panel unter „PHP-Einstellungen“ umstellen.',
    'check.help_ext' => 'Die Erweiterung {name} muss aktiviert sein. Beim Hoster nachfragen.',
    'check.help_outgoing' => 'Ohne eine der beiden Möglichkeiten kann die Ortssuche nicht arbeiten.',
    'check.help_contact' => 'In lib/config.php bei LH_USER_AGENT eine echte Adresse eintragen, etwa: lighthours/1.0 (+https://deine-domain.de; du@deine-domain.de). OpenStreetMap lehnt Anfragen mit Platzhaltern mit HTTP 403 ab – das ist die mit Abstand häufigste Ursache, wenn die Suche nichts findet.',
    'check.help_calc' => 'Bitte alle Dateien erneut hochladen.',
    'check.help_tzdb' => 'Die PHP-Zeitzonendatenbank scheint unvollständig zu sein. Beim Hoster melden.',
    'check.help_geo' => 'Erreicht der Server nominatim.openstreetmap.org? Manche Hoster sperren ausgehende Verbindungen und geben sie erst auf Anfrage frei.',
    'check.help_geo_empty' => 'Die Anfrage lief durch, lieferte aber nichts. Bitte später erneut versuchen.',
    'check.help_cache' => 'Optional. Ein Verzeichnis cache/ mit Schreibrechten entlastet OpenStreetMap.',
    'check.all_ok' => 'Alles bereit.',
    'check.all_ok_text' => 'Zur Startseite – und check.php kann weg.',
    'check.not_ok' => 'Es fehlt noch etwas.',
    'check.not_ok_text' => 'Die Hinweise oben nennen jeweils den nächsten Schritt. Nach einer Änderung einfach diese Seite neu laden.',
    'check.manual' => 'Noch von Hand zu prüfen',
    'check.manual_text' => 'Ruf lib/config.php auf. Es muss ein Fehler 403 erscheinen. Wird stattdessen die Datei angezeigt oder heruntergeladen, wertet dein Hoster die .htaccess nicht aus – dann die Verzeichnisse lib, lang, data, partials und cache über das Hosting-Panel sperren.',
    'check.to_home' => 'Zur Startseite',
    'gen.size_hint' => 'Sehr lange Zeiträume erzeugen große Dateien – fünf Jahre mit allen Terminarten sind rund 3 MB. Manche Kalender-Apps laden das spürbar langsam. Zwei Jahre als rollierendes Abo sind meist die bessere Wahl.',

    // Eigenbezeichnung der Sprache – erscheint in der Auswahl
    'lang.name' => 'Deutsch',

    // Bildschirmfotos
    'screens.title' => 'Und so sieht es im Kalender aus',
    'screens.intro' => 'Keine App, kein Konto – die Termine stehen einfach da, wo du ohnehin nachschaust.',
    'screens.week_alt' => 'Zwei aufeinanderfolgende Tage in der Wochenansicht, jeder mit einem Termin für die Goldene Stunde',
    'screens.week_cap' => 'Zwei aufeinanderfolgende Tage in Berlin: 18:52, dann 18:54. Die Goldene Stunde wandert täglich um ein bis zwei Minuten – genau das behält niemand im Kopf.',
    'screens.event_alt' => 'Geöffneter Termin mit Ort, Uhrzeit und Beschreibung',
    'screens.event_cap' => 'Jeder Termin nennt Beginn, Ende und Ort. Sprache und Beschreibung stammen aus deinen Einstellungen beim Erstellen.',
    'screens.note' => 'Bildschirmfotos aus Apple Kalender. In Google Kalender und Outlook erscheinen dieselben Termine.',

    // Anonyme Zählung
    'stats.line' => 'lighthours hält gerade <strong>{count} Kalender</strong> aktuell.',
    'stats.note' => 'Gezählt werden abonnierte Kalender, keine Personen – wer mehrere Regionen plant, erscheint mehrfach. Gespeichert wird dabei weder eine IP-Adresse noch eine Browserkennung, sondern nur, dass ein Kalender abgerufen wurde.',

    // Rechtstexte
    'legal.privacy_title' => 'Datenschutz',
    'legal.imprint_title' => 'Impressum',
    'legal.updated' => 'Stand: {date}',
    'legal.only_de_en' => 'Diese Seite liegt nur auf Deutsch und Englisch vor. Maßgeblich ist die deutsche Fassung.',
    'footer.imprint' => 'Impressum',

    // Suchmaschinen und soziale Netzwerke
    'meta.og_alt' => 'lighthours – Goldene und Blaue Stunde als Kalender-Abo',
    'meta.privacy_desc' => 'Was lighthours speichert und was nicht: keine Cookies, keine Konten, keine Werbenetzwerke. Vollständige Datenschutzerklärung.',
    'meta.imprint_desc' => 'Anbieterkennzeichnung und Kontakt für lighthours, den quelloffenen Kalender für Goldene und Blaue Stunde.',
    'meta.check_desc' => 'Selbstprüfung der Installation.',
    'os.link' => 'Quellcode auf GitHub',
];
