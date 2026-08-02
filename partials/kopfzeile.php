<?php
/**
 * Sichtbare Kopfzeile: Marke, Farbmodus, Sprachwahl.
 *
 * Stand vorher dreimal fast gleich in index.php, check.php und
 * partials/rechtstext.php – mit den erwartbaren Abweichungen. Jetzt an einer
 * Stelle.
 *
 * Die Sprachwahl ist eine Auswahlliste aus <details> statt einer Reihe von
 * Kürzeln. Sechs nebeneinander drängen auf dem Telefon die Marke aus dem Bild;
 * und mit jeder weiteren Sprache würde es schlimmer. <details> braucht kein
 * JavaScript, ist mit der Tastatur bedienbar und schließt sich beim Wechsel
 * von selbst, weil die Seite neu lädt.
 *
 * Erwartet: $i18n, $lang, $seiteName
 *
 * @var LightHours\I18n $i18n
 * @var string $lang
 * @var string $seiteName
 */

declare(strict_types=1);

use LightHours\I18n;

/**
 * Ziel der Sprungmarke. Sie ist das erste fokussierbare Element und lässt
 * Tastatur- und Screenreader-Nutzer die Kopfzeile überspringen.
 */
$sprungziel = '#' . match ($seiteName) {
    'index' => 'generator',
    'check' => 'ergebnis',
    default => 'inhalt',
};

/** Adresse derselben Seite in einer anderen Sprache */
$sprachAdresse = static function (string $sprache) use ($seiteName): string {
    $datei = $seiteName === 'index' ? '' : $seiteName . '.php';

    return './' . $datei . '?lang=' . $sprache;
};
?>
<svg style="display:none" aria-hidden="true">
  <symbol id="icon-auto" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="1.8"/>
    <path d="M12 4a8 8 0 0 0 0 16Z" fill="currentColor"/>
  </symbol>
  <symbol id="icon-sun" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="4.6" fill="none" stroke="currentColor" stroke-width="1.8"/>
    <path d="M12 2.6v2.2M12 19.2v2.2M4.2 12H2M22 12h-2.2M6.5 6.5 4.9 4.9M19.1 19.1l-1.6-1.6M17.5 6.5l1.6-1.6M4.9 19.1l1.6-1.6"
          stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
  </symbol>
  <symbol id="icon-moon" viewBox="0 0 24 24">
    <path d="M20 14.2A8.4 8.4 0 0 1 9.8 4a8.4 8.4 0 1 0 10.2 10.2Z"
          fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
  </symbol>
  <symbol id="icon-chevron" viewBox="0 0 24 24">
    <path d="m6 9 6 6 6-6" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round"/>
  </symbol>
</svg>

<a class="skip-link" href="<?= LightHours\h($sprungziel) ?>"><?= $i18n->e('nav.skip') ?></a>

<header class="site-header">
  <div class="wrap">
    <a class="brand" href="<?= LightHours\h($sprachAdresse($lang)) ?>">
      <svg viewBox="0 0 64 64" aria-hidden="true" focusable="false">
        <rect x="10" y="17"   width="44" height="7" rx="3.5" fill="var(--gold-400)"/>
        <rect x="15" y="28.5" width="34" height="7" rx="3.5" fill="var(--gold-500)"/>
        <rect x="20" y="40"   width="24" height="7" rx="3.5" fill="var(--blue-500)"/>
      </svg>
      <span class="brand-name">lighthours</span>
    </a>

    <div class="header-tools">
      <button type="button" class="theme-toggle" id="theme-toggle"
              title="<?= $i18n->e('theme.auto') ?>" aria-label="<?= $i18n->e('theme.auto') ?>"
              data-label-auto="<?= $i18n->e('theme.auto') ?>"
              data-label-light="<?= $i18n->e('theme.light') ?>"
              data-label-dark="<?= $i18n->e('theme.dark') ?>">
        <svg aria-hidden="true"><use href="#icon-auto" id="theme-icon"/></svg>
      </button>

      <details class="lang-menu" id="lang-menu">
        <summary aria-label="<?= $i18n->e('nav.language') ?>">
          <span class="lang-current"><?= strtoupper(LightHours\h($lang)) ?></span>
          <span class="lang-full"><?= LightHours\h(I18n::nativeName($lang)) ?></span>
          <svg class="lang-chevron" aria-hidden="true"><use href="#icon-chevron"/></svg>
        </summary>

        <ul class="lang-list">
          <?php foreach (I18n::available() as $l): ?>
            <li>
              <a href="<?= LightHours\h($sprachAdresse($l)) ?>" lang="<?= LightHours\h($l) ?>"
                 <?= $l === $lang ? 'aria-current="true"' : '' ?>>
                <span class="lang-code"><?= strtoupper(LightHours\h($l)) ?></span>
                <?= LightHours\h(I18n::nativeName($l)) ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </details>
    </div>
  </div>
</header>
