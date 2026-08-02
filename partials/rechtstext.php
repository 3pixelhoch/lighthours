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

<?php require __DIR__ . '/kopfzeile.php'; ?>

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
