<?php
/**
 * Datenschutzerklärung, deutsche Fassung.
 *
 * Inhaltlich beschreibt sie exakt, was die Anwendung tatsächlich tut. Wer den
 * Code ändert – etwa den E-Mail-Versand einschaltet oder eine eigene
 * Nominatim-Instanz einträgt – muss diesen Text entsprechend anpassen.
 *
 * Dies ist kein anwaltlich geprüfter Text. Für den produktiven Einsatz mit
 * geschäftlichem Bezug sollte er von einer fachkundigen Person durchgesehen
 * werden.
 */

declare(strict_types=1);

return <<<'HTML'
<h2>Kurz vorweg</h2>
<p>
  lighthours kommt ohne Konten, ohne Cookies und ohne Werbenetzwerke aus. Es gibt
  keine Nutzerprofile, keine Wiedererkennung über Besuche hinweg und keinen
  Weiterverkauf von Daten. Was trotzdem an Daten anfällt, steht vollständig hier.
</p>

<h2>Verantwortlich</h2>
<p>
  Die im <a href="impressum.php">Impressum</a> genannte Stelle ist auch für die
  Datenverarbeitung auf dieser Website verantwortlich.
</p>

<h2>Aufruf der Website</h2>
<p>
  Beim Abruf einer Seite überträgt dein Browser technisch notwendige Angaben an
  den Server: IP-Adresse, Zeitpunkt, aufgerufene Adresse, Browserkennung. Diese
  Daten fallen bei jedem Webserver an und werden vom Hosting-Anbieter in
  Server-Protokollen gespeichert. Rechtsgrundlage ist Art. 6 Abs. 1 lit. f DSGVO
  – das berechtigte Interesse am technisch fehlerfreien Betrieb. Die Anwendung
  selbst legt keine eigenen Zugriffsprotokolle an und wertet diese Daten nicht
  aus. Zur Speicherdauer der Server-Protokolle gilt, was dein Hosting-Anbieter
  in seiner Datenschutzerklärung angibt.
</p>

<h2>Keine Cookies</h2>
<p>
  Es werden keine Cookies gesetzt – weder eigene noch fremde. Gespeichert wird
  ausschließlich deine Wahl des Farbmodus (hell, dunkel oder Systemvorgabe) im
  lokalen Speicher deines Browsers unter dem Schlüssel <code>lh-theme</code>.
  Dieser Wert verlässt dein Gerät nie und wird nicht an den Server übertragen.
  Er entsteht nur, wenn du den Umschalter tatsächlich benutzt, und lässt sich
  über die Einstellungen deines Browsers jederzeit löschen.
</p>

<h2>Ortssuche</h2>
<p>
  Wenn du im Kalendergenerator einen Ort eingibst, wird dein Suchbegriff an
  <a href="https://nominatim.openstreetmap.org" rel="noopener">Nominatim</a>
  weitergegeben, den Suchdienst der OpenStreetMap Foundation. Diese Anfrage
  stellt <strong>der Server</strong>, nicht dein Browser. OpenStreetMap erfährt
  dadurch deinen Suchbegriff, aber nicht deine IP-Adresse.
</p>
<p>
  Suchergebnisse werden für sieben Tage auf dem Server zwischengespeichert, um
  den kostenlosen Dienst zu entlasten. Gespeichert werden dabei nur der
  Suchbegriff und das Ergebnis, nichts, was auf dich zurückführt.
  Rechtsgrundlage ist Art. 6 Abs. 1 lit. b DSGVO – ohne diese Abfrage lässt sich
  der gewünschte Kalender nicht erstellen.
</p>

<h2>Kartendarstellung</h2>
<p>
  Sobald du einen Ort ausgewählt hast, erscheint eine Karte. Die Kartenkacheln
  lädt <strong>dein Browser direkt</strong> von
  <a href="https://www.openstreetmap.org" rel="noopener">tile.openstreetmap.org</a>.
  Dabei erfährt die OpenStreetMap Foundation deine IP-Adresse und den
  Kartenausschnitt. Vorher wird die Karte nicht geladen – wer nur die Startseite
  liest, baut keine Verbindung dorthin auf. Es gilt die
  <a href="https://wiki.osmfoundation.org/wiki/Privacy_Policy" rel="noopener">Datenschutzerklärung
  der OpenStreetMap Foundation</a>.
</p>

<h2>Schriften und Programmbibliotheken</h2>
<p>
  Sämtliche Schriften und die Kartenbibliothek liegen auf diesem Server. Es
  werden <strong>keine</strong> Google Fonts, keine Content Delivery Networks und
  keine externen Skripte eingebunden.
</p>

<h2>Anonyme Zählung aktiver Kalender</h2>
<p>
  In der Fußzeile steht, wie viele Kalender gerade aktiv sind. Diese Zahl kommt
  so zustande: Abonnierte Kalender werden von deiner Kalender-App regelmäßig
  abgerufen. Bei jedem Abruf berechnet der Server einen 16-stelligen Hashwert
  aus den <em>Kalendereinstellungen</em> – gerundete Koordinaten, gewählte
  Terminarten, Zeitraum – und legt nur diesen Hash in einer Tagesdatei ab.
</p>
<p>
  Nicht gespeichert werden dabei: IP-Adresse, Browserkennung, Uhrzeit oder
  irgendein Merkmal, das zwei Kalender derselben Person zuordnen könnte. Die
  Koordinaten fließen auf zwei Nachkommastellen gerundet ein, also etwa
  kilometergenau. Dateien, die älter als 40 Tage sind, werden gelöscht.
  Rechtsgrundlage ist Art. 6 Abs. 1 lit. f DSGVO – das berechtigte Interesse an
  einer groben Nutzungsstatistik ohne Personenbezug.
</p>

<h2>Kalenderabonnement</h2>
<p>
  Ein Kalenderabo ist eine gewöhnliche Internetadresse. Alle Einstellungen –
  Koordinaten, Zeitraum, Terminarten – stehen in dieser Adresse selbst. Auf dem
  Server wird zu deinem Kalender <strong>nichts</strong> gespeichert; er wird bei
  jedem Abruf neu berechnet. Wer die Adresse kennt, kann den Kalender abrufen,
  behandle sie also wie einen persönlichen Link.
</p>

<h2>Versand per E-Mail</h2>
<p>
  Sofern der Betreiber diese Funktion eingeschaltet hat, kannst du dir den
  Kalenderlink freiwillig per E-Mail schicken lassen. Deine Adresse wird
  ausschließlich für diese eine Nachricht verwendet und danach <strong>nicht
  gespeichert</strong> – es entsteht kein Verteiler und kein Newsletter.
  Rechtsgrundlage ist Art. 6 Abs. 1 lit. a DSGVO, deine Einwilligung durch das
  Absenden.
</p>
<p>
  Zum Schutz vor Missbrauch wird die Zahl der Nachrichten je Stunde begrenzt.
  Dafür speichert der Server einen gekürzten, tagesweise gesalzenen Hash deiner
  IP-Adresse für höchstens zwei Stunden. Aus diesem Wert lässt sich die
  IP-Adresse nicht zurückgewinnen.
</p>

<h2>Unterstützung</h2>
<p>
  Der Knopf „Kaffee ausgeben“ ist ein gewöhnlicher Link zu Buy Me a Coffee. Es
  ist <strong>kein Skript</strong> dieses Anbieters eingebunden – erst wenn du
  klickst, verlässt du diese Seite. Dort gelten dann die Bestimmungen des
  Anbieters.
</p>

<h2>Deine Rechte</h2>
<p>
  Dir stehen die Rechte aus Art. 15 bis 21 DSGVO zu: Auskunft, Berichtigung,
  Löschung, Einschränkung der Verarbeitung, Datenübertragbarkeit und Widerspruch.
  Ein Hinweis dazu in aller Offenheit: Da hier keine personenbezogenen Daten in
  einer Form gespeichert werden, die dir zugeordnet werden könnte, lässt sich zu
  einer Auskunftsanfrage in aller Regel schlicht nichts finden. Wende dich
  trotzdem gern an die im Impressum genannte Adresse.
</p>
<p>
  Außerdem hast du das Recht, dich bei einer Datenschutz-Aufsichtsbehörde zu
  beschweren, Art. 77 DSGVO.
</p>

<h2>Änderungen</h2>
<p>
  Diese Erklärung beschreibt den Stand der Software zum unten genannten Datum.
  Da lighthours quelloffen ist, lässt sich jederzeit im Quellcode nachprüfen, ob
  sie noch zutrifft.
</p>
HTML;
