<?php
/**
 * Wie ein Termin im Kalender aussieht.
 *
 * Als eingebettetes SVG statt als Bild: Die Karte enthält Text, und zwölf
 * gerenderte Dateien (sechs Sprachen mal hell und dunkel) müssten bei jeder
 * Textänderung neu erzeugt werden. So übersetzt sie sich mit den Sprachdateien
 * und folgt dem Farbmodus ohne zweite Fassung.
 *
 * Bewusst kein Nachbau einer bestimmten Kalender-App: Daneben steht ein echtes
 * Bildschirmfoto, das den Beweis liefert. Diese Karte zeigt nur, was drinsteht.
 *
 * Zeiten und Ort sind ein Beispiel (Bremen, 4. August) und stehen fest - sie
 * müssen zu den Werten im Lichtdiagramm passen.
 *
 * @var LightHours\I18n $i18n
 */

declare(strict_types=1);
?>
<svg class="event-card" viewBox="0 0 604 420" role="img"
     aria-labelledby="ec-t" preserveAspectRatio="xMidYMid meet">
  <title id="ec-t"><?= $i18n->e('screens.event_alt') ?></title>

  <rect x="1" y="1" width="602" height="418" rx="16"
        fill="var(--card)" stroke="var(--border)" stroke-width="1.5"/>

  <rect x="46" y="52" width="6" height="30" rx="3" fill="var(--gold-500)"/>
  <text x="66" y="70" class="ec-title"><?= $i18n->e('event.golden_evening') ?></text>
  <text x="66" y="91" class="ec-muted">Bremen</text>

  <line x1="46" y1="112" x2="558" y2="112" stroke="var(--border)"/>

  <text x="46" y="146" class="ec-time">20:24 – 21:39</text>
  <text x="46" y="170" class="ec-muted"><?= $i18n->e('screens.card_date') ?></text>

  <rect x="46" y="192" width="512" height="112" rx="12"
        fill="var(--accent-weak)"/>

  <text x="68" y="224" class="ec-muted"><?= $i18n->e('chart.sunset') ?></text>
  <text x="536" y="224" text-anchor="end" class="ec-value">21:13</text>

  <text x="68" y="254" class="ec-muted"><?= $i18n->e('screens.card_above') ?></text>
  <text x="536" y="254" text-anchor="end" class="ec-value">20:24 – 21:13</text>

  <line x1="68" y1="272" x2="536" y2="272" stroke="var(--gold-400)" opacity="0.35"/>
  <text x="68" y="292" class="ec-note"><?= $i18n->e('screens.card_alarms') ?></text>

  <text x="46" y="352" class="ec-muted"><?= $i18n->e('screens.card_source') ?></text>
</svg>
