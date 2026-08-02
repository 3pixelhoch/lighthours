# lighthours – Designsystem

Alles Visuelle steckt in `assets/css/tokens.css`. Wer das Erscheinungsbild ändern
will, ändert diese eine Datei – Komponenten greifen ausschließlich auf Tokens zu,
nie auf feste Werte.

---

## Idee

Zwei Lichtstimmungen tragen das gesamte System: das warme Gold der Goldenen Stunde
und das kühle Blau der Blauen Stunde. Alles andere bleibt zurückhaltend, damit die
Fotos der Nutzer – nicht die Oberfläche – die Farbe im Raum sind.

Die Gestaltung ist flach und geometrisch: keine Verläufe, keine Schlagschatten als
Schmuck, keine Illustrationen. Form entsteht aus Fläche, Abstand und Typografie.

---

## Bildmarke

Drei gestapelte Balken, von oben nach unten schmaler werdend und von Gold nach Blau
laufend: der Horizont als Lichtbänder. Der Verlauf ist die Aussage – warmes Licht
oben, Dämmerung unten.

```
▁▁▁▁▁▁▁▁▁▁▁▁   #E09A42   Gold 400   Goldene Stunde
  ▁▁▁▁▁▁▁▁     #C97B2C   Gold 500   Übergang
    ▁▁▁▁       #3C5A8F   Blau 500   Blaue Stunde
```

| Datei | Zweck |
|---|---|
| `assets/img/mark.svg` | Bildmarke allein |
| `assets/img/logo.svg` | Wortmarke mit Schriftzug, Buchstaben als Pfade (schriftunabhängig) |
| `assets/img/favicon.svg` | Favicon – dickere Balken, weniger Rand |
| `assets/img/favicon.ico` | Rückfallebene für ältere Browser (16/32/48 px) |
| `assets/img/apple-touch-icon.png` | 180 px auf Papierfarbe (iOS mag keine Transparenz) |
| `assets/img/icon-32/96/192/512.png` | Web-App-Manifest und Vorschaubilder |

**Zwei Zeichnungen, ein Zeichen.** Die Balken der Favicon-Fassung sind dicker und
haben weniger Rand. Bei 16 px verschwimmen die dünneren Balken der Hauptfassung,
die dickeren bleiben getrennt lesbar. Das ist kein zweites Logo, sondern dieselbe
Marke in einer Auszeichnung für kleine Größen.

> Die Bildmarke steht **nicht** unter der MIT-Lizenz des Quellcodes – siehe
> [NOTICE.md](NOTICE.md). Wer forkt, gestaltet bitte eine eigene.

**Regeln**

- Schutzraum ringsum: mindestens die Höhe eines Balkens.
- Kleinste Größe: 16 px, dort die Favicon-Fassung verwenden.
- Die drei Farben sind fest. Die Marke funktioniert unverändert auf Hell und Dunkel
  und wird nicht eingefärbt.
- Nicht drehen, nicht verzerren, Balkenabstände nicht verändern.
- In `logo.svg` steht der Schriftzug in Pfaden – die Datei braucht keine Schrift und
  kann überall eingebettet werden. Die Textfarbe folgt `currentColor`, Vorgabe ist
  `#16171B`.
- Im Verhältnis zur Wortmarke steht die Marke auf Faktor 1,30 und ist optisch auf
  die Mitte zwischen Grundlinie und Oberlänge gesetzt.

---

## Farben

### Goldene Stunde

| Token | Wert | Verwendung |
|---|---|---|
| `--gold-100` | `#FDF3E3` | Flächen hinter Hinweisen |
| `--gold-300` | `#F2BE6B` | Akzent im Dunkelmodus |
| `--gold-400` | `#E09A42` | oberster Balken der Marke |
| `--gold-500` | `#C97B2C` | **Primärfarbe** – Schaltflächen, Marker, mittlerer Balken |
| `--gold-600` | `#A25F1E` | Hover-Zustand |

### Blaue Stunde

| Token | Wert | Verwendung |
|---|---|---|
| `--blue-400` | `#4E6FA5` | Punkte vor den blauen Terminarten |
| `--blue-500` | `#3C5A8F` | **Sekundärfarbe**, unterster Balken der Marke |

Die Palette führt bewusst nur die Stufen, die auch benutzt werden. Eine
dokumentierte Farbe, die nirgends vorkommt, ist ein Versprechen ohne Deckung.

### Fehler

| Token | Hell | Dunkel |
|---|---|---|
| `--danger` | `#B3261E` (6,3:1) | `#F2B8B5` (11,2:1) |
| `--danger-weak` | `#FCEEEC` | Rot mit 12 % Deckung |

Ein eigener Ton, kein Gold: Gold ist die Farbe der Primäraktion. Dieselbe Farbe
für „hat geklappt" und „ist schiefgegangen" nimmt beiden Meldungen die
Eindeutigkeit.

### Neutrale Töne

Bewusst leicht warm – reines Weiß wirkt neben dem Gold klinisch.

| Token | Hell | Dunkel |
|---|---|---|
| `--paper` (Hintergrund) | `#FBFAF8` | `#0E1014` |
| `--surface` (Karten) | `#FFFFFF` | `#171A20` |
| `--line` (Linien) | `#E7E3DC` | `#262A32` |
| `--ink` (Text) | `#16171B` | `#ECEAE6` |
| `--muted` (Nebentext) | `#6C7076` | `#969AA3` |

### Gold hat zwei Rollen

Ein einziger Goldton kann nicht beides: hell genug für eine einladende Fläche und
dunkel genug für lesbaren Text auf Papier. Deshalb zwei Tokens:

| Token | Hell | Dunkel | Rolle |
|---|---|---|---|
| `--accent` | `#E09A42` | `#F2BE6B` | **Flächen**: Schaltflächen, Punkte, Marker |
| `--accent-text` | `#A25F1E` | `#F2BE6B` | **Text und feine Linien** auf hellem Grund |
| `--on-accent` | `#17130C` | `#17130C` | Text **auf** goldener Fläche |

Nie `color: #fff` auf Gold schreiben – Weiß erreicht auf `#E09A42` nur 2,3:1.
Dunkler Text kommt auf 7,8:1.

Gefüllte Schaltflächen bekommen zusätzlich einen Rand in `--accent-text`. Die helle
Goldfläche allein hebt sich mit 2,3:1 zu schwach vom Papier ab; mit Rand sind es
4,8:1 und WCAG 1.4.11 ist erfüllt.

### Gemessene Kontraste

| | Hell | Dunkel | Soll |
|---|---|---|---|
| Fließtext auf Hintergrund | 17,2 | 15,9 | ≥ 4,5 |
| Nebentext auf Hintergrund | 4,8 | 6,8 | ≥ 4,5 |
| Text auf goldener Fläche | 7,8 | 10,9 | ≥ 4,5 |
| Akzenttext auf Hintergrund | 4,8 | 11,2 | ≥ 4,5 |
| Akzenttext auf getönter Fläche | 4,6 | 9,0 | ≥ 4,5 |
| Rand der Schaltfläche | 4,8 | 11,2 | ≥ 3,0 |

---

## Dunkelmodus

Zwei Wege führen hin:

1. **Systemvorgabe** – greift über `prefers-color-scheme`, solange nichts gewählt wurde
2. **Manuelle Wahl** – `data-theme` am `<html>`-Element, gesetzt vom Umschalter
   in der Kopfzeile

Der Umschalter läuft dreistufig: **System → Hell → Dunkel**. „System" ist die
Vorgabe und speichert nichts; erst eine bewusste Wahl landet unter `lh-theme` im
`localStorage`. Kein Cookie, keine Übertragung an den Server.

Ein winziges Skript im `<head>` liest den Wert **vor** dem ersten Zeichnen. Ohne
das blitzt beim Laden für einen Moment der falsche Modus auf.

Die Dunkel-Werte stehen in `tokens.css` zweimal – einmal für die Systemvorgabe,
einmal für die manuelle Wahl. Ohne Bauwerkzeug lässt sich ein Block in CSS nicht
wiederverwenden. Damit die beiden nicht auseinanderlaufen, vergleicht
`tests/run.php` sie bei jedem Lauf.

`color-scheme` wird mitgeführt, damit auch Formularelemente, Bildlaufleisten und
die Adressleiste mobiler Browser zum gewählten Modus passen.

---

## Typografie

**Outfit** für alles – Überschriften, Fließtext, Formulare, Wortmarke. Eine
geometrische Grotesk, variabel über die Achse `wght` (100–900), SIL-OFL-lizenziert
und lokal eingebunden. Es geht **kein einziger Aufruf an Google**.

Zwei Dateien in `assets/fonts/`, zusammen 45 KB:
`outfit-var-latin.woff2` und `outfit-var-latin-ext.woff2`. Der Browser lädt über
`unicode-range` nur, was er wirklich braucht.

**Laufweite.** Geometrische Schriften wirken bei großen Graden zu luftig, deshalb:

| Token | Wert | Einsatz |
|---|---|---|
| `--tracking-tight` | −0,035 em | große Überschriften, Wortmarke |
| `--tracking-snug` | −0,02 em | mittlere Überschriften |

Fließtext läuft ohne Anpassung.

**Gewichte:** 400 Fließtext, 500 Beschriftungen, 600 Überschriften und
Schaltflächen, 700 Auszeichnungen. Mehr wird nicht gebraucht.

**Größen** skalieren fließend mit dem Viewport (`clamp()`), ganz ohne Media Queries:

| Token | Bereich |
|---|---|
| `--text-sm` | 0,88 rem |
| `--text-base` | 1 rem |
| `--text-xl` | 1,25 – 1,45 rem |
| `--text-2xl` | 1,6 – 2,1 rem |
| `--text-3xl` | 2,1 – 3,4 rem |
| `--text-4xl` | 2,6 – 4,6 rem |

Zeilenlänge ist auf `--measure` (62 Zeichen) begrenzt.

**Wortmarke:** durchgehend klein geschrieben, `lighthours`, Gewicht 600, Laufweite
`--tracking-tight`.

---

## Abstände

4-px-Raster, `--sp-1` bis `--sp-10` (0,25 rem bis 8 rem). Großzügiger Weißraum ist
Teil der Gestaltung: Abschnitte trennen sich durch Luft und eine Haarlinie, nicht
durch Kästen und Schatten.

## Form

| Token | Wert | Einsatz |
|---|---|---|
| `--radius-sm` | 6 px | Fokusrahmen |
| `--radius` | 12 px | Eingabefelder, Karte |
| `--radius-lg` | 18 px | Karten und Schritte |
| `--radius-pill` | 999 px | Schaltflächen, Sprachumschaltung |

Die Balken der Bildmarke sind vollständig abgerundet (Radius = halbe Höhe) und
nehmen damit dieselbe Sprache auf wie die Pillen-Schaltflächen.

Schatten sind sehr zurückhaltend (`--shadow-sm` und `--shadow`) und dienen nur
der Ebenentrennung, nie der Dekoration.

---

## Komponenten

**Schaltflächen** – `.btn` plus `.btn-primary` (gold, gefüllt), `.btn-outline`
(Kontur) oder `.btn-ghost` (nur Text). Immer als Pille, immer mit sichtbarem
Fokusring.

**Auswahlkacheln** – `.check` umschließt Beschriftung und Checkbox, ist also
vollflächig anklickbar; im gewählten Zustand goldener Rahmen und getönte Fläche
(`:has(input:checked)`).

**Schritte** – `.step` nummeriert sich über einen CSS-Zähler selbst. Schritt 2 bis 5
sind ausgeblendet, bis ein Ort gewählt wurde: Der Nutzer sieht anfangs nur ein
einziges Eingabefeld.

**Karte** – Marker in `--gold-500`, Radiuskreis als goldene Fläche mit 10 % Deckung
und dünner Kontur.

**Unterstützen** – ein eigener Abschnitt am Seitenende (`.support`) mit Tasse,
einem Satz zum Warum und der Schaltfläche; dazu ein Link in der Fußzeile. Beides
sind schlichte Links in Projektfarben. Steuerung über `LH_COFFEE_USER` in
`lib/config.php`, leerer Wert blendet beides aus.

> Bewusst **nicht** das offizielle Buy-me-a-coffee-Widget: Das lädt ein Skript von
> einem fremden CDN und würde die Datenschutzaussage der Seite aushebeln. Ein
> eigener Link erreicht dasselbe und passt farblich besser.

---

## Zwei Regeln für den Dunkelmodus

Jede Regel, die im Dunkelmodus anders aussehen soll, braucht **beide** Wege:

```css
@media (prefers-color-scheme: dark) {
  :root:not([data-theme='light']) .beispiel { … }
}
:root[data-theme='dark'] .beispiel { … }
```

Nur die Media Query zu schreiben ist ein stiller Fehler: Wer ein helles System
hat und manuell auf Dunkel stellt, sieht dann die helle Fassung auf dunklem
Grund. Besser ist es ohnehin, gar keine modusabhängige Regel zu schreiben,
sondern ein Token zu benutzen – die kennen beide Wege bereits.

**Ausnahme:** `<meta name="theme-color">` in den drei PHP-Seiten trägt einen
festen Hexwert, weil Meta-Angaben keine CSS-Variablen auswerten. Das Skript
setzt ihn beim Moduswechsel neu. Ebenso die SVG-Dateien unter `assets/img/` –
sie müssen eigenständig funktionieren, etwa als Favicon oder eingebettet in
fremde Dokumente. Die drei Inline-Marken in den PHP-Seiten nutzen dagegen die
Tokens.

---

## Barrierefreiheit

- Sichtbarer Fokusring auf allen bedienbaren Elementen (`:focus-visible`).
- Sprungmarke „Zum Inhalt springen“ als erstes fokussierbares Element.
- Statusmeldungen (Suche, Abweichung) mit `role="status"`, damit Screenreader sie
  vorlesen.
- Jede Farbcodierung hat eine Textentsprechung – die goldenen und blauen Punkte
  vor den Terminarten sind reine Zugabe.
- `prefers-reduced-motion` schaltet alle Übergänge ab.
- Bedienelemente mindestens 44 px hoch.

---

## Was bewusst fehlt

Keine Verläufe, keine Schlagschatten als Schmuck, keine Illustrationen, keine
Animationen beim Scrollen, kein Cookie-Banner, kein fremdes Skript. Die Seite soll
sich anfühlen wie ein gut gesetztes Werkzeug – ruhig, präzise, schnell.
