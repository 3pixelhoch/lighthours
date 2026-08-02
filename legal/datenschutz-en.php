<?php
/**
 * Privacy statement, English version.
 *
 * Courtesy translation. The German version is the authoritative one, because
 * the site is operated from Germany and the GDPR terms are defined there.
 */

declare(strict_types=1);

return <<<'HTML'
<h2>In short</h2>
<p>
  lighthours works without accounts, without cookies and without advertising
  networks. There are no user profiles, no recognition across visits and no
  selling of data. Everything that does occur is described below.
</p>

<h2>Controller</h2>
<p>
  The entity named in the <a href="impressum.php">legal notice</a> is also
  responsible for data processing on this website.
</p>

<h2>Visiting the site</h2>
<p>
  When you open a page, your browser transmits technically necessary details to
  the server: IP address, time, requested address, browser identification. This
  happens with every web server and is stored by the hosting provider in server
  logs. The legal basis is Art. 6(1)(f) GDPR – the legitimate interest in
  technically sound operation. The application itself keeps no access logs of
  its own and does not evaluate this data. Retention of the server logs follows
  whatever your hosting provider states in its own privacy statement.
</p>

<h2>No cookies</h2>
<p>
  No cookies are set – neither first-party nor third-party. The only thing
  stored is your choice of colour mode (light, dark or system) in your browser's
  local storage under the key <code>lh-theme</code>. That value never leaves
  your device and is not transmitted to the server. It is only created if you
  actually use the switch, and can be deleted through your browser settings at
  any time.
</p>

<h2>Place search</h2>
<p>
  When you enter a place in the calendar generator, your search term is passed
  to <a href="https://nominatim.openstreetmap.org" rel="noopener">Nominatim</a>,
  the search service of the OpenStreetMap Foundation. That request is made by
  <strong>the server</strong>, not by your browser. OpenStreetMap therefore
  learns your search term, but not your IP address.
</p>
<p>
  Search results are cached on the server for seven days to reduce load on the
  free service. Only the search term and the result are stored, nothing that
  points back to you. The legal basis is Art. 6(1)(b) GDPR – without this
  lookup the requested calendar cannot be created.
</p>

<h2>Map display</h2>
<p>
  Once you have selected a place, a map appears. The map tiles are loaded
  <strong>directly by your browser</strong> from
  <a href="https://www.openstreetmap.org" rel="noopener">tile.openstreetmap.org</a>.
  The OpenStreetMap Foundation thereby learns your IP address and the map
  section. Before that the map is not loaded at all – anyone who only reads the
  home page establishes no connection there. The
  <a href="https://wiki.osmfoundation.org/wiki/Privacy_Policy" rel="noopener">OpenStreetMap
  Foundation privacy policy</a> applies.
</p>

<h2>Fonts and libraries</h2>
<p>
  All fonts and the map library are hosted on this server. <strong>No</strong>
  Google Fonts, no content delivery networks and no external scripts are
  embedded.
</p>

<h2>Anonymous count of active calendars</h2>
<p>
  The footer states how many calendars are currently active. That number comes
  about as follows: subscribed calendars are fetched regularly by your calendar
  app. On each fetch the server derives a 16-character hash from the
  <em>calendar settings</em> – rounded coordinates, selected event types, period
  – and stores only that hash in a per-day file.
</p>
<p>
  Not stored are: IP address, browser identification, time of day, or any
  attribute that could link two calendars to the same person. Coordinates enter
  the hash rounded to two decimal places, roughly one kilometre. Files older
  than 40 days are deleted. The legal basis is Art. 6(1)(f) GDPR – the
  legitimate interest in rough usage figures without any personal reference.
</p>

<h2>Calendar subscription</h2>
<p>
  A calendar subscription is an ordinary internet address. All settings –
  coordinates, period, event types – are contained in that address itself.
  <strong>Nothing</strong> about your calendar is stored on the server; it is
  recalculated on every request. Anyone who knows the address can retrieve the
  calendar, so treat it like a personal link.
</p>

<h2>Sending by email</h2>
<p>
  If the operator has enabled this feature, you may optionally have the calendar
  link sent to you by email. Your address is used for that single message and is
  <strong>not stored</strong> afterwards – no mailing list, no newsletter. The
  legal basis is Art. 6(1)(a) GDPR, your consent given by submitting the form.
</p>
<p>
  To prevent abuse, the number of messages per hour is limited. For that the
  server stores a shortened, daily-salted hash of your IP address for at most
  two hours. The IP address cannot be recovered from that value.
</p>

<h2>Support</h2>
<p>
  The “Buy me a coffee” button is an ordinary link to Buy Me a Coffee.
  <strong>No script</strong> from that provider is embedded – you only leave this
  site once you click. Their terms apply from that point on.
</p>

<h2>Your rights</h2>
<p>
  You hold the rights under Art. 15 to 21 GDPR: access, rectification, erasure,
  restriction of processing, data portability and objection. One note in all
  openness: since no personal data is stored here in a form that could be
  attributed to you, a request for access will as a rule simply turn up nothing.
  You are nonetheless welcome to write to the address given in the legal notice.
</p>
<p>
  You also have the right to lodge a complaint with a supervisory authority,
  Art. 77 GDPR.
</p>

<h2>Changes</h2>
<p>
  This statement describes the state of the software as of the date given below.
  Because lighthours is open source, you can verify in the source code at any
  time whether it still holds.
</p>
HTML;
