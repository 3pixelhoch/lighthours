<?php
/**
 * Gerüst für Datenschutzerklärung und Impressum.
 *
 * Beide Seiten teilen sich dieses Gerüst und binden ihren Text aus /legal ein.
 * Erwartet vor dem Einbinden: $seite ('datenschutz' oder 'impressum').
 *
 * Rechtstexte liegen bewusst nur auf Deutsch und Englisch vor: Die deutsche
 * Fassung ist maßgeblich, weil von Deutschland aus betrieben wird. Für die
 * übrigen Sprachen erscheint die englische Fassung mit einem Hinweis darauf.
 *
 * @var string $seite
 */

declare(strict_types=1);

require_once __DIR__ . '/../lib/bootstrap.php';

use LightHours\I18n;

$lang = I18n::detect($_GET['lang'] ?? null);
$i18n = new I18n($lang);

function e(string $key, array $vars = []): string
{
    global $i18n;
    return $i18n->e($key, $vars);
}

$textLang = $lang === 'de' ? 'de' : 'en';
$datei    = __DIR__ . '/../legal/' . $seite . '-' . $textLang . '.php';
$inhalt   = is_file($datei) ? (string) require $datei : '';

$titel = $i18n->t($seite === 'datenschutz' ? 'legal.privacy_title' : 'legal.imprint_title');
$datei_zeit = is_file($datei) ? filemtime($datei) : time();
?>
<!DOCTYPE html>
<html lang="<?= LightHours\h($lang) ?>">
<head>
<?php
$seiteName    = $seite;
$titel        = $titel . ' – lighthours';
$beschreibung = $i18n->t($seite === 'datenschutz' ? 'meta.privacy_desc' : 'meta.imprint_desc');
require __DIR__ . '/kopf.php';
?>
</head>
<body>

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
</svg>

<a class="skip-link" href="#inhalt"><?= e('nav.skip') ?></a>

<header class="site-header">
  <div class="wrap">
    <a class="brand" href="./?lang=<?= LightHours\h($lang) ?>">
      <svg viewBox="0 0 64 64" aria-hidden="true" focusable="false">
        <rect x="10" y="17"   width="44" height="7" rx="3.5" fill="var(--gold-400)"/>
        <rect x="15" y="28.5" width="34" height="7" rx="3.5" fill="var(--gold-500)"/>
        <rect x="20" y="40"   width="24" height="7" rx="3.5" fill="var(--blue-500)"/>
      </svg>
      <span class="brand-name">lighthours</span>
    </a>

    <div class="header-tools">
      <button type="button" class="theme-toggle" id="theme-toggle"
              title="<?= e('theme.auto') ?>" aria-label="<?= e('theme.auto') ?>"
              data-label-auto="<?= e('theme.auto') ?>"
              data-label-light="<?= e('theme.light') ?>"
              data-label-dark="<?= e('theme.dark') ?>">
        <svg aria-hidden="true"><use href="#icon-auto" id="theme-icon"/></svg>
      </button>

      <nav class="lang-switch" aria-label="<?= e('nav.language') ?>">
        <?php foreach (I18n::available() as $l): ?>
          <a href="<?= LightHours\h($seite) ?>.php?lang=<?= LightHours\h($l) ?>"
             title="<?= LightHours\h(I18n::nativeName($l)) ?>"<?= $l === $lang ? ' aria-current="true"' : '' ?>><?= strtoupper(LightHours\h($l)) ?></a>
        <?php endforeach; ?>
      </nav>
    </div>
  </div>
</header>

<main class="wrap wrap-narrow legal" id="inhalt">
  <h1><?= LightHours\h($titel) ?></h1>

<?php if ($textLang !== $lang): ?>
  <p class="legal-notice"><?= e('legal.only_de_en') ?></p>
<?php endif; ?>

  <?= $inhalt ?>

  <p class="legal-date"><?= e('legal.updated', ['date' => date('d.m.Y', $datei_zeit)]) ?></p>
</main>

<?php require __DIR__ . '/footer.php'; ?>

<script>window.LH = { lang: <?= json_encode($lang) ?>, base: <?= json_encode(LightHours\base_url()) ?>, t: {} };</script>
<script src="assets/js/app.js" defer></script>
</body>
</html>
