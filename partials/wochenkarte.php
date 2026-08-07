<?php
/**
 * Zwei aufeinanderfolgende Tage in der Wochenansicht.
 *
 * Zeigt den einzigen Punkt, um den es geht: Die Goldene Stunde wandert
 * täglich um ein bis zwei Minuten. 18:52, dann 18:54.
 *
 * Wie die Terminkarte als eingebettetes SVG, damit die Beschriftungen aus den
 * Sprachdateien kommen. Die echten Bildschirmfotos aus Apple Kalender liegen
 * im README - dort ist der Nachweis wichtiger als ein einheitliches Bild.
 *
 * @var LightHours\I18n $i18n
 */

declare(strict_types=1);

/** 18:00 liegt auf y = 118, eine Stunde misst 58 Pixel */
$y = static fn(int $stunde, int $minute): float => 118 + ($stunde - 18) * 58 + $minute * 58 / 60;
?>
<svg class="week-card" viewBox="0 0 604 420" role="img"
     aria-labelledby="wc-t" preserveAspectRatio="xMidYMid meet">
  <title id="wc-t"><?= $i18n->e('screens.week_alt') ?></title>

  <rect x="1" y="1" width="602" height="418" rx="16"
        fill="var(--card)" stroke="var(--border)" stroke-width="1.5"/>

  <?php foreach ([['gen.d2', '4', 84], ['gen.d3', '5', 324]] as [$tag, $zahl, $x]): ?>
    <text x="<?= $x + 120 ?>" y="66" text-anchor="middle" class="wc-day"><?= $i18n->e($tag) ?></text>
    <text x="<?= $x + 120 ?>" y="46" text-anchor="middle" class="wc-num"><?= $zahl ?></text>
  <?php endforeach; ?>

  <line x1="40" y1="82" x2="564" y2="82" stroke="var(--border)" stroke-width="1.5"/>
  <line x1="324" y1="82" x2="324" y2="380" stroke="var(--border)"/>

  <?php foreach ([18, 19, 20, 21, 22] as $stunde): ?>
    <line x1="84" y1="<?= $y($stunde, 0) ?>" x2="564" y2="<?= $y($stunde, 0) ?>"
          stroke="var(--border)"/>
    <text x="74" y="<?= $y($stunde, 0) + 5 ?>" text-anchor="end" class="wc-hour"><?= $stunde ?>:00</text>
  <?php endforeach; ?>

  <?php
  $termine = [
      [ 92, $y(18, 52), $y(19, 58), '18:52 – 19:58'],
      [332, $y(18, 54), $y(20,  0), '18:54 – 20:00'],
  ];
  foreach ($termine as [$x, $von, $bis, $zeit]):
  ?>
    <rect x="<?= $x ?>" y="<?= $von ?>" width="224" height="<?= $bis - $von ?>" rx="6"
          fill="var(--accent-weak)"/>
    <rect x="<?= $x ?>" y="<?= $von ?>" width="4" height="<?= $bis - $von ?>" rx="2"
          fill="var(--gold-500)"/>
    <text x="<?= $x + 16 ?>" y="<?= $von + 24 ?>" class="wc-event"><?= $zeit ?></text>
    <text x="<?= $x + 16 ?>" y="<?= $von + 45 ?>" class="wc-name"><?= $i18n->e('event.golden_evening') ?></text>
  <?php endforeach; ?>
</svg>
