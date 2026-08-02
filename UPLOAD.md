# Upload auf die Subdomain

Der Ordner **`Website Online`** enthält genau das, was auf den Server gehört –
nichts weiter. Doku, Tests und die Designvorschau bleiben bewusst draußen.

---

## 1. Vor dem Upload: eine Zeile ändern

In `Website Online/lib/config.php`:

```php
const LH_USER_AGENT = 'lighthours/1.0 (+https://deine-domain.de; du@deine-domain.de)';
```

Das ist keine Formalität. Nominatim, die kostenlose Ortssuche von OpenStreetMap,
sperrt Zugriffe ohne erreichbare Kontaktadresse – ohne diesen Eintrag findet die
Suche nichts.

Optional in derselben Datei:

```php
const LH_COFFEE_USER = '';  // leer = Kaffee-Button aus
const LH_SOURCE_URL  = '';            // Adresse des Quellcodes, leer = Verweis aus
const LH_MAIL_ENABLED = false;        // freiwilliger E-Mail-Versand
const LH_STATS_PUBLIC = true;         // Zahl aktiver Kalender in der Fußzeile
```

`LH_BASE_URL` kann leer bleiben – die Adresse wird automatisch erkannt.

---

## 2. Hochladen

Den **Inhalt** von `Website Online` in das Wurzelverzeichnis der Subdomain legen,
nicht den Ordner selbst. Danach muss `index.php` direkt unter
`https://deine-domain.de/index.php` liegen.

```
deine-domain.de/
├── index.php
├── calendar.php
├── .htaccess          ← unbedingt mit hochladen, viele FTP-Programme
│                        blenden Dateien mit führendem Punkt aus
├── site.webmanifest
├── api/  lang/  lib/  assets/
```

**PHP-Version auf 8.1 oder neuer stellen.** Meist im Hosting-Panel unter
„PHP-Einstellungen“. Ältere Versionen brechen ab, weil der Code moderne Syntax
nutzt.

---

## 3. Danach prüfen

Ruf **`https://deine-domain.de/check.php`** auf. Die Seite prüft PHP-Version,
Erweiterungen, ausgehende Verbindungen, die Kontaktadresse, die Berechnung und die
Ortssuche – und nennt bei jedem Fehlschlag den nächsten Schritt.

Steht dort überall ein Haken, kannst du `check.php` löschen.

Einen Punkt prüft die Seite nicht selbst: Ruf `…/lib/config.php` auf. Es muss ein
**404** erscheinen. Wird stattdessen die Datei angezeigt, wertet dein Hoster die
`.htaccess` nicht aus – dann die Verzeichnisse `lib`, `lang` und `cache` über das
Hosting-Panel sperren.

---

## 4. Optional: Zwischenspeicher

Ein Verzeichnis `cache/` mit Schreibrechten beschleunigt wiederholte Ortssuchen und
entlastet OpenStreetMap. Es wird von selbst angelegt, sofern die Rechte es zulassen.
Fehlt es, arbeitet alles weiter – nur eben ohne Zwischenspeicher.

---

## 5. Beim Aktualisieren: was bleiben muss

Alle Dateien lassen sich bedenkenlos überschreiben — bis auf zwei Dinge.

### `cache/` niemals löschen

Das ist das **einzige** Verzeichnis, in dem die Anwendung schreibt:

| Inhalt | Bedeutung |
|---|---|
| `cache/stats/` | die Zahl aktiver Kalender in der Fußzeile |
| `cache/limits/` | Missbrauchsbremse des E-Mail-Versands |
| `cache/v2_geo_*.json` | zwischengespeicherte Ortssuchen |

Der Ordner liegt bewusst **nicht** im Ordner `Website Online`. Wer im
FTP-Programm einen Abgleich mit Löschen benutzt („Synchronisieren", „Mirror",
„Nicht vorhandene Dateien entfernen"), löscht ihn damit auf dem Server mit.

Stell dein FTP-Programm auf **Hochladen und Überschreiben** um, ohne Löschen.
Oder nimm `cache` in die Ausschlussliste auf.

**Halb so wild, wenn es doch passiert:** Alles darin baut sich von selbst wieder
auf. Die Ortssuchen werden bei Bedarf neu geholt, und die Kalenderzahl steigt
binnen eines Tages wieder auf den richtigen Stand — denn jede Kalender-App, die
ein Abo hält, ruft die Adresse täglich ab und wird dabei erneut gezählt.

### `lib/config.php` mit Bedacht

Diese Datei trägt deine Kontaktadresse, die Basis-URL und den Coffee-Namen. In
`Website Online` steht die richtige Fassung, sie kann also überschrieben werden.
Wer aber direkt auf dem Server etwas geändert hat, überschreibt es damit.

### `check.php` nach dem Einrichten löschen

Sie wird nur zum Prüfen gebraucht und beim nächsten Upload wieder mitkommen.
Einfach danach wieder entfernen.

---

## 6. Was ein Upload **nicht** kaputtmachen kann

Bestehende Abonnements. Ein Abo ist nichts als eine Adresse in der Kalender-App
des Nutzers; auf dem Server steht dazu nichts. Der Kalender wird bei jedem
Abruf neu berechnet.

Solange `calendar.php` erreichbar bleibt und die Domain dieselbe ist, laufen
alle Abos weiter — auch wenn du jede einzelne Datei austauschst.

---

## 7. Abo-Links brauchen HTTPS

Kalender-Abos ruft nicht dein Gerät ab, sondern der Server des Kalenderanbieters:

| Kalender | ruft ab von | braucht öffentliche URL |
|---|---|---|
| Apple Kalender | deinem Gerät | nein |
| Google Kalender | Googles Servern | **ja** |
| Outlook Web | Microsofts Servern | **ja** |

Auf einer öffentlichen Subdomain ist das erfüllt. Ein gültiges HTTPS-Zertifikat
sollte trotzdem stehen – viele Kalender-Apps verweigern `webcal://` über
unverschlüsselte Verbindungen. Let's Encrypt reicht und ist bei fast allen Hostern
mit einem Klick aktiviert.

---

## Beim nächsten Mal

`Website Online` wird bei jeder Änderung neu erzeugt. Einfach wieder komplett
hochladen und überschreiben – es gibt keine Datenbank und keinen Zustand, der
verloren gehen könnte. Nur `lib/config.php` mit deinen Einträgen vorher sichern
oder danach erneut anpassen.
