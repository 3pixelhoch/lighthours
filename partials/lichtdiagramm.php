<?php
/**
 * Warum die Goldene Stunde nach Sonnenuntergang endet.
 *
 * Bewusst ohne Uhrzeiten: Die Dauer der Phasen hängt von Breitengrad und
 * Jahreszeit ab. Eine Grafik mit "20:24 bis 21:39" wäre in Nairobi und in
 * Reykjavík schlicht falsch. Die Sonnenhöhen dagegen gelten überall.
 *
 * Die Kurve ist ein echter Verlauf (Bremen, 4. August), nur ohne Beschriftung
 * der Zeitachse - sie soll die Neigung realistisch zeigen, nicht Werte liefern.
 *
 *
 * @var LightHours\I18n $i18n
 * @var string $lang
 */

declare(strict_types=1);

?>
<figure class="phase-chart">
  <h3><?= $i18n->e('chart.heading') ?></h3>

  <svg viewBox="0 0 680 375" role="img" aria-labelledby="pc-t pc-d" preserveAspectRatio="xMidYMid meet">
    <title id="pc-t"><?= $i18n->e('chart.svg_title') ?></title>
    <desc id="pc-d"><?= $i18n->e('chart.svg_desc') ?></desc>

    <line x1="90" y1="76"  x2="184" y2="76"  stroke="var(--fg-muted)" stroke-width="0.75" stroke-dasharray="2 3"/>
    <line x1="90" y1="166" x2="476" y2="166" stroke="var(--fg-muted)" stroke-width="0.75" stroke-dasharray="2 3"/>
    <line x1="90" y1="184" x2="538" y2="184" stroke="var(--fg-muted)" stroke-width="0.75" stroke-dasharray="2 3"/>

    <line x1="184" y1="76"  x2="184" y2="232" stroke="var(--fg-muted)" stroke-width="0.75" stroke-dasharray="2 3"/>
    <line x1="476" y1="166" x2="476" y2="232" stroke="var(--fg-muted)" stroke-width="0.75" stroke-dasharray="2 3"/>
    <line x1="538" y1="184" x2="538" y2="232" stroke="var(--fg-muted)" stroke-width="0.75" stroke-dasharray="2 3"/>

    <line x1="90" y1="130" x2="616" y2="130"
          stroke="var(--fg-muted)" stroke-width="1.5"/>

    <path d="M 90 45 L 148 64 L 207 83 L 265 101 L 324 118 L 382 136 L 441 155 L 499 172 L 558 188 L 616 204"
          fill="none" stroke="var(--gold-500)" stroke-width="2.5" stroke-linejoin="round"/>

    <circle cx="184" cy="76"  r="4" fill="var(--gold-500)"/>
    <circle cx="476" cy="166" r="4" fill="var(--gold-500)"/>
    <circle cx="538" cy="184" r="4" fill="var(--blue-500)"/>
    <circle cx="382" cy="130" r="5.5" fill="var(--gold-500)"/>

    <line x1="382" y1="130" x2="382" y2="262"
          stroke="var(--gold-500)" stroke-width="1.75" stroke-dasharray="4 4"/>

    <text x="80" y="81"  text-anchor="end" class="pc-ax">+6°</text>
    <text x="80" y="126" text-anchor="end" class="pc-ax">0°</text>
    <text x="80" y="171" text-anchor="end" class="pc-ax">−4°</text>
    <text x="80" y="189" text-anchor="end" class="pc-ax">−6°</text>
    <text x="612" y="122" text-anchor="end" class="pc-ax"><?= $i18n->e('chart.horizon') ?></text>

    <rect x="184" y="232" width="292" height="26" rx="4"
          fill="var(--gold-400)" opacity="0.22"/>
    <rect x="382" y="232" width="94"  height="26"
          fill="var(--gold-400)" opacity="0.45"/>
    <rect x="476" y="232" width="62"  height="26" rx="4"
          fill="var(--blue-500)" opacity="0.22"/>

    <text x="382" y="282" text-anchor="middle" class="pc-mark"><?= $i18n->e('chart.sunset') ?></text>

    <rect x="90" y="303" width="13" height="13" rx="3" fill="var(--gold-400)" opacity="0.22"/>
    <text x="112" y="314" class="pc-ax"><?= $i18n->e('chart.legend_golden') ?></text>
    <rect x="90" y="325" width="13" height="13" rx="3" fill="var(--gold-400)" opacity="0.45"/>
    <text x="112" y="336" class="pc-ax"><?= $i18n->e('chart.legend_after') ?></text>
    <rect x="90" y="347" width="13" height="13" rx="3" fill="var(--blue-500)" opacity="0.22"/>
    <text x="112" y="358" class="pc-ax"><?= $i18n->e('chart.legend_blue') ?></text>
  </svg>

  <figcaption class="phase-chart-text">
    <p><?= $i18n->e('chart.text_1') ?></p>
    <p><?= $i18n->e('chart.text_2') ?></p>
  </figcaption>
</figure>
