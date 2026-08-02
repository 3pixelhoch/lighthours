<picture>
  <source media="(prefers-color-scheme: dark)" srcset="assets/img/readme-dunkel.png">
  <img alt="lighthours – Goldene Stunde, direkt im Kalender" src="assets/img/readme-hell.png">
</picture>

[![Fassung](https://img.shields.io/badge/Fassung-1.0.0-C97B2C)](CHANGELOG.md)
[![Lizenz](https://img.shields.io/badge/Lizenz-MIT-3C5A8F)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4)](https://www.php.net/)

**[lighthours.app](https://lighthours.app)** · [English version](README.md)

**Goldene und Blaue Stunde als Kalender-Abo – für jeden Ort der Welt.**

lighthours berechnet die täglichen Lichtzeiten für beliebige Koordinaten und liefert
sie als abonnierbaren iCal-Kalender. Reines PHP und JavaScript, keine Datenbank,
keine Abhängigkeiten, kein Composer. Hochladen und läuft.

---

## Installation

1. Den kompletten Ordner per FTP in das Web-Verzeichnis hochladen
   (`httpdocs`, `public_html`, `www` – je nach Hoster).
2. In `lib/config.php` die Kontaktadresse eintragen:

   ```php
   const LH_USER_AGENT = 'lighthours/1.0 (+https://deine-domain.de; du@deine-domain.de)';
   ```

   Das ist keine Formalität: Nominatim, die kostenlose Ortssuche von OpenStreetMap,
   sperrt Zugriffe ohne erreichbare Kontaktadresse.
3. Fertig. Die Seite ist aufrufbar.
4. `check.php` im Browser aufrufen – die Seite prüft die Installation und meldet,
   was noch fehlt. Danach kann sie gelöscht werden.

**Voraussetzung:** PHP 8.1 oder neuer. Erforderliche Erweiterungen (`mbstring`,
`json`, `date`) sind überall vorhanden; für die Ortssuche wird `curl` genutzt, sonst
`allow_url_fopen`.

**Optional:** Ein beschreibbares Verzeichnis `cache/` beschleunigt die Ortssuche und
entlastet OpenStreetMap. Es wird automatisch angelegt, sofern die Rechte es
erlauben – fehlt es, arbeitet lighthours ohne Zwischenspeicher weiter.

### Lokal ausprobieren

```bash
php -S localhost:8000
```

---

## Aufbau

```
index.php              Startseite und Generator
calendar.php           Kalenderausgabe (ICS)
check.php              Selbstprüfung nach dem Upload
datenschutz.php        Datenschutzerklärung
impressum.php          Impressum
sitemap.php            Sitemap, erzeugt aus dem Sprachbestand
robots.txt
api/
  times.php            Lichtzeiten als JSON
  geocode.php          Ortssuche (Nominatim-Proxy)
  deviation.php        Zeitabweichung im Radius
  send.php             Kalenderlink per E-Mail (freiwillig)
legal/
  datenschutz-de|en    Rechtstexte (Deutsch maßgeblich)
  impressum-de|en
partials/
  kopf.php             Dokumentkopf: Titel, Metaangaben, Suchmaschinen
  footer.php           gemeinsamer Seitenfuß
  rechtstext.php       Gerüst für die Rechtsseiten
lib/
  Sun.php              Sonnenstand (NOAA-Algorithmus)
  LightPhases.php      Goldene und Blaue Stunde
  Ics.php              iCalendar-Erzeugung mit VTIMEZONE
  Timezone.php         Zeitzone aus Koordinaten
  Geocoder.php         Ortssuche
  Cache.php            Dateizwischenspeicher
  Mailer.php           E-Mail-Versand (SMTP oder mail())
  RateLimit.php        Missbrauchsbremse
  I18n.php             Mehrsprachigkeit
  config.php           Konfiguration
lang/
  de en it fr es       Übersetzungen
assets/                Schriften, Bilder, CSS, JS, MapLibre
  img/screens/         Bildschirmfotos für die Startseite (hell und dunkel)
```

Die Module unter `lib/` kennen einander nur, wo es nötig ist, und lassen sich einzeln
verwenden. Die Verzeichnisse `lib/`, `lang/` und `cache/` sind per `.htaccess` von
außen gesperrt.

---

## API

Alle Endpunkte antworten mit JSON und erlauben Zugriffe von fremden Domains (CORS).

### Lichtzeiten

```
GET api/times.php?lat=53.5511&lon=9.9937&days=3&tz=Europe/Berlin&lang=de
```

```json
{
  "lat": 53.5511, "lon": 9.9937, "timezone": "Europe/Berlin",
  "days": [{
    "date": "2026-08-01",
    "phases": [{
      "event": "blue_morning",
      "label": "Blaue Stunde (Morgen)",
      "start": "2026-08-01T04:50:58+02:00",
      "end":   "2026-08-01T05:08:21+02:00",
      "start_local": "04:50", "end_local": "05:08",
      "duration_minutes": 17
    }]
  }]
}
```

### Ortssuche

```
GET api/geocode.php?q=Hamburg&lang=de
```

Liefert Name, Koordinaten, Ländercode und passende Zeitzonen.

`country` gewichtet mehrdeutige Eingaben: Die Postleitzahl 20095 gibt es in
Hamburg, in Cusano Milanino und – als 20-095 – in Lublin. Ohne Angabe wird das
Land aus dem `Accept-Language`-Header des Browsers abgeleitet.

### Zeitabweichung im Radius

```
GET api/deviation.php?lat=53.55&radius=100
```

### Kalender

```
GET calendar.php?lat=53.5511&lon=9.9937&months=24&rolling=1
```

| Parameter | Werte | Vorgabe |
|---|---|---|
| `lat`, `lon` | Koordinaten | Pflicht |
| `events` | `golden_morning`, `golden_evening`, `blue_morning`, `blue_evening` (kommagetrennt) | alle |
| `months` | 1 – 60 | 12 |
| `end` | eigenes Enddatum `JJJJ-MM-TT` (statt `months`) | – |
| `rolling` | `1` = rollierend, immer ab Abrufdatum | aus |
| `tz` | Zeitzonen-Kennung | aus Koordinaten geschätzt |
| `lang` | `de`, `en`, `it`, `fr`, `es` | `de` |
| `reminder` | Minuten vor Beginn | keine |
| `name` | Anzeigename des Orts | Koordinaten |

Für ein Abo `rolling=1` verwenden: Der Kalender wandert dann mit und ist nie leer.

---

## Genauigkeit

Der Sonnenstand wird nach dem NOAA-Verfahren berechnet, inklusive atmosphärischer
Refraktion – gerechnet wird also mit der *scheinbaren* Sonnenhöhe, so wie die Sonne
tatsächlich am Himmel steht. Nahe am Horizont macht das rund zwei Minuten aus.

Phasengrenzen:

| Phase | Sonnenhöhe |
|---|---|
| Goldene Stunde | −4° bis +6° |
| Blaue Stunde | −6° bis −4° |

Gegen eine unabhängige Referenzimplementierung (Python/Astral) geprüft: über ein
volles Jahr an sechs Orten von Reykjavík bis Sydney beträgt die mittlere Abweichung
**0,9 Sekunden**, die größte 3 Sekunden. In Polarnähe kann die Sonne eine Grenze
streifen statt sie zu kreuzen – dort ist der Zeitpunkt naturgemäß unscharf, die
Phase erstreckt sich dann ohnehin über Stunden.

An Tagen ohne die jeweilige Phase (Polartag, Polarnacht) entfällt der Termin
ersatzlos, statt eine falsche Zeit zu erfinden.

---

## Datenschutz

- Keine Cookies, keine Sitzungen, kein Tracking, keine Zugriffsprotokolle.
- Gespeichert wird einzig die Wahl des Farbmodus – im `localStorage` des Browsers,
  und auch nur, wenn jemand den Umschalter benutzt. Nichts davon erreicht den Server.
- Die Schrift liegt lokal (`assets/fonts/`) – **kein Aufruf an Google Fonts**.
- MapLibre liegt lokal (`assets/vendor/`) – kein CDN.
- Die Ortssuche läuft serverseitig: Suchbegriffe erreichen OpenStreetMap ohne die
  IP-Adresse des Besuchers.
- Einzige Ausnahme: Die **Kartenkacheln** lädt der Browser direkt von
  `tile.openstreetmap.org`, sobald ein Ort gewählt wurde. Vorher wird die Karte gar
  nicht geladen. Wer auch das vermeiden will, hinterlegt in `assets/js/app.js` eine
  eigene Kachelquelle.

---

## E-Mail-Versand

Ausgeschaltet, solange `LH_MAIL_ENABLED` in `lib/config.php` auf `false` steht.
Eingeschaltet erscheint im letzten Schritt ein Feld, über das sich der Abo-Link
verschicken lässt – freiwillig, nie Voraussetzung für den Kalender.

Es wird **nichts gespeichert**: keine Adresse, keine Liste, kein Protokoll. Die
Nachricht geht raus, danach ist die Adresse vergessen.

Gegen Missbrauch: höchstens `LH_MAIL_MAX_PER_HOUR` Nachrichten je Stunde und
Besucher, ein für Menschen unsichtbares Feld gegen Skripte, und der Kalenderlink
muss auf die eigene Installation zeigen – sonst wäre das Formular ein offener
Versandapparat.

Auf günstigem Webspace ist `mail()` oft gesperrt oder landet im Spam. Dann in
`lib/config.php` einen SMTP-Zugang eintragen (`LH_SMTP_HOST` und folgende); der
eingebaute SMTP-Client kommt ohne zusätzliche Bibliothek aus.

---

## Unterstützung

In `lib/config.php` steht `LH_COFFEE_USER`. Ist dort ein Buy-me-a-coffee-Benutzername
hinterlegt, erscheinen ein eigener Abschnitt am Seitenende und ein Link in der
Fußzeile – beides in den Projektfarben. Leerer Wert blendet beides aus.

Bewusst als eigener Link statt als offizielles BMC-Widget: Dessen Skript kommt von
einem fremden CDN und würde den Datenschutzabschnitt oben hinfällig machen.

---

## Mehrsprachigkeit

Neue Sprache: `lang/de.php` kopieren, Werte übersetzen, als `lang/<code>.php`
speichern. Die Sprache erscheint automatisch in der Umschaltung – am Code ist nichts
zu ändern. Kalendertermine, Beschreibungen und Oberfläche folgen derselben Datei.

---

## Design

Bildmarke sind drei gestapelte Balken von Gold nach Blau – der Horizont als
Lichtbänder. Schrift ist Outfit, eine geometrische Grotesk, lokal eingebunden
(45 KB). Farben, Größen, Abstände und die Regeln zur Marke stehen in
[DESIGN.md](DESIGN.md), sämtliche Werte in `assets/css/tokens.css`.

Hell- und Dunkelmodus folgen der Systemeinstellung; ein Umschalter in der Kopfzeile
erlaubt System, Hell oder Dunkel.

Zum Anschauen ohne Server: `lighthours-designvorschau.html` im Browser öffnen.

---

## Vor dem Onlinestellen

Sobald der Quellcode veröffentlicht ist, `LH_SOURCE_URL` in `lib/config.php`
setzen – dann erscheint der Verweis „Quellcode" in der Fußzeile. Solange der
Wert leer ist, bleibt der Verweis ausgeblendet, statt ins Leere zu zeigen.

Die Datenschutzerklärung beschreibt exakt, was die Anwendung tut. Wer den Code
ändert – etwa den E-Mail-Versand einschaltet oder die Zählung abschaltet –
sollte `legal/datenschutz-*.php` entsprechend anpassen.

---

## Änderungen

Siehe [CHANGELOG.md](CHANGELOG.md).

---

## Lizenz

MIT – siehe [LICENSE](LICENSE). Verwenden, ändern, weitergeben, auch
kommerziell einsetzen: alles erlaubt.

**Nicht Teil der Lizenz sind der Name „lighthours" und die Bildmarke.** Forks
und eigene Instanzen sind ausdrücklich willkommen, aber bitte unter eigenem
Namen – siehe [NOTICE.md](NOTICE.md). Eine unveränderte Instanz zu betreiben und
dabei auf lighthours zu verweisen, ist selbstverständlich in Ordnung.

Schrift: Outfit steht unter der SIL Open Font License 1.1.
MapLibre GL JS steht unter der 3-Clause-BSD-Lizenz.
Ortsdaten: © OpenStreetMap-Mitwirkende, ODbL.
