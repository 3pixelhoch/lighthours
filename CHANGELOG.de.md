# Änderungen

[English version](CHANGELOG.md)

Das Format folgt [Keep a Changelog](https://keepachangelog.com/de/1.1.0/),
die Nummerierung [Semantic Versioning](https://semver.org/lang/de/).

## [1.2.0] – 2026-08-07

### Abonnieren

- Eigene Knöpfe für Google Kalender und Outlook. Bisher gab es nur `webcal://`;
  unter Windows und Android ist für dieses Schema oft kein Programm
  registriert, und dann passiert beim Klick nichts – ohne jede Meldung
- Der Kalender ist zusätzlich unter einer Adresse ohne Fragezeichen erreichbar
  (`/calendar.php/<token>/lighthours.ics`). Google Kalender braucht das: Die
  `&` einer Parameterliste zerlegen Googles eigene Adresse, kodiert versteht
  Google den Wert nicht mehr. Die bisherige Schreibweise gilt unverändert weiter
- Hinweis, dass Google neue Termine etwa einmal am Tag holt

### Website

- Der Generator lässt sich über die Adresse vorbelegen:
  `?lat=…&lon=…&name=…` springt direkt zur Auswahl. Damit lassen sich fertige
  Einstellungen weitergeben

## [1.1.0] – 2026-08-07

### Kalender

- Goldene Stunden nennen den Abschnitt, in dem die Sonne über dem Horizont
  steht – nur dort kommt das Licht von der Seite und modelliert
- Wochentage lassen sich einschränken: Ein Abo über zwei Jahre schrumpft damit
  von 2928 auf wenige hundert Termine
- Zweite, frühere Erinnerung zum Vorbereiten, bewusst nur an den Goldenen
  Stunden, damit nicht acht Meldungen am Tag entstehen
- Jeder Termin nennt Sonnenauf- beziehungsweise -untergang als Bezugspunkt
- Eigene Knöpfe für Google Kalender und Outlook: webcal:// allein läuft unter
  Windows und Android oft ins Leere, ohne dass jemand eine Meldung sieht
- Der Generator lässt sich über die Adresse vorbelegen
  (`?lat=…&lon=…&name=…`), womit sich fertige Einstellungen teilen lassen

### Website

- Grafik auf der Startseite erklärt, warum die Goldene Stunde erst nach
  Sonnenuntergang endet
- Sichtbarer Weg, falsche Zeiten zu melden

### Behoben

- Das Logo führte auf den Rechtsseiten nicht zur Startseite, sondern auf die
  Seite, auf der man schon stand
- Ohne Ländercode lieferte die Zeitzonenwahl „UTC" statt „Atlantic/Reykjavik" –
  zeitlich richtig, als Kalenderbeschriftung falsch
- Die API gab Koordinaten mit siebzehn Nachkommastellen aus
- Suchfeld und Knopf standen auf dem Telefon nebeneinander statt untereinander

## [1.0.0] – 2026-08-02

Erste vollständige Fassung. Läuft auf jedem Webspace mit PHP 8.1: hochladen,
eine Zeile in `lib/config.php` eintragen, fertig. Keine Datenbank, kein
Composer, keine Abhängigkeiten.

### Kalender

- Goldene und Blaue Stunde, morgens und abends, für jeden Ort der Welt
- Abonnierbarer iCal-Kalender und ICS-Download
- Zeitraum von drei Monaten bis fünf Jahren oder eigenes Enddatum
- Rollierendes Abo, das mit dem Datum mitwandert und nie leer läuft
- Optionale Erinnerung 15, 30 oder 60 Minuten vor Beginn
- Echte Zeitzonenangabe samt Sommerzeitwechseln (VTIMEZONE)
- Stabile Termin-Kennungen, sodass Kalender-Apps keine Dubletten anlegen
- Jeder Termin nennt den Sonnenauf- beziehungsweise -untergang als Bezugspunkt:
  Die Goldene Stunde endet abends erst rund 25 Minuten nach dem Untergang

### Berechnung

- Sonnenstand nach dem vollständigen NOAA-Verfahren mit atmosphärischer
  Refraktion – gerechnet wird mit der scheinbaren Sonnenhöhe
- Gegen eine unabhängige Referenz geprüft: über ein volles Jahr an sechs Orten
  von Reykjavík bis Sydney im Mittel 0,9 Sekunden Abweichung, maximal drei
- Termine werden am Ortsdatum verankert, nicht am UTC-Datum
- In Polarnähe entfallen nicht existierende Phasen, statt eine Zeit zu erfinden

### Ortssuche

- Eingabe von Stadt, Adresse, Region oder Postleitzahl – nie Koordinaten
- Zeitzone wird aus dem Ort abgeleitet; gleich tickende Zonen werden auf die
  geläufige Schreibweise zusammengefasst
- Mehrdeutige Postleitzahlen werden nach Herkunftsland gewichtet
- Läuft serverseitig, damit die IP-Adresse der Besucher OpenStreetMap nicht
  erreicht; Ergebnisse werden sieben Tage zwischengespeichert

### Oberfläche

- Karte mit verschiebbarem Mittelpunkt und wählbarem Gültigkeitsradius
- Anzeige der maximalen zeitlichen Abweichung im gewählten Umkreis
- Vorschau der nächsten Termine noch vor dem Abonnieren
- Hell- und Dunkelmodus, wahlweise nach Systemvorgabe oder manuell
- Sechs Sprachen: Deutsch, Englisch, Italienisch, Französisch, Spanisch,
  Portugiesisch – auch die Kalendertermine selbst

### Datenschutz

- Keine Cookies, keine Konten, keine Werbenetzwerke, kein Tracking
- Schriften und Kartenbibliothek liegen lokal; kein Aufruf an Google oder ein CDN
- Kartenkacheln werden erst nach Auswahl eines Orts geladen
- Anonyme Zählung aktiver Kalender über einen Hash der Kalendereinstellungen,
  ohne IP-Adresse, ohne Zeitstempel
- Datenschutzerklärung und Impressum als eigene Seiten

### Betrieb

- `check.php` prüft die Installation und benennt konkret, was fehlt
- Deutliche Meldung, solange die Kontaktadresse für die Ortssuche fehlt –
  die häufigste Stolperfalle beim ersten Aufsetzen
- Freiwilliger E-Mail-Versand des Abo-Links über SMTP oder `mail()`,
  ab Werk ausgeschaltet, mit Missbrauchsbremse
- 163 Tests ohne Framework: `php tests/run.php`

### Suchmaschinen

- Kanonische Adressen, Sprachalternativen und `x-default`
- Vorschaubilder für soziale Netzwerke in allen sechs Sprachen
- Strukturierte Daten als `WebApplication`
- `robots.txt` und eine Sitemap, die neue Sprachen selbsttätig aufnimmt

### Gestaltung

- Bildmarke aus drei gestapelten Balken: der Horizont als Lichtbänder
- Outfit als einzige Schrift, variabel und lokal eingebunden (45 KB)
- Sämtliche Farben, Größen und Abstände in `assets/css/tokens.css`
- Alle Kontrastwerte erfüllen in beiden Modi WCAG AA
