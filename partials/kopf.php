<?php
/**
 * Gemeinsamer Dokumentkopf für alle Seiten.
 *
 * Bündelt an einer Stelle, was Suchmaschinen und soziale Netzwerke auswerten:
 * Titel, Beschreibung, kanonische Adresse, Sprachalternativen, Vorschaubild
 * und strukturierte Daten. Vorher stand das dreifach in den Seiten und lief
 * erwartungsgemäß auseinander.
 *
 * Erwartet vor dem Einbinden:
 *   $i18n      LightHours\I18n
 *   $lang      aktueller Sprachcode
 *   $seiteName Dateiname ohne Endung, z. B. 'index' oder 'datenschutz'
 *   $titel     vollständiger Seitentitel
 *   $beschreibung Kurzbeschreibung für Suchergebnisse
 *   $indexieren  optional, false hält Suchmaschinen fern
 *
 * @var LightHours\I18n $i18n
 * @var string $lang
 * @var string $seiteName
 * @var string $titel
 * @var string $beschreibung
 */

declare(strict_types=1);

use LightHours\I18n;

$basis      = LightHours\base_url();
$indexieren = $indexieren ?? true;

/** Adresse einer Seite in einer bestimmten Sprache */
$adresse = static function (string $sprache) use ($basis, $seiteName): string {
    $datei = $seiteName === 'index' ? '' : $seiteName . '.php';

    return $basis . '/' . $datei . ($sprache === '' ? '' : ($datei === '' ? '?lang=' : '?lang=') . $sprache);
};

$kanonisch = $adresse($lang);
$vorschau  = $basis . '/assets/img/og-' . $lang . '.png';

/* Open Graph erwartet Sprache samt Region, nicht nur den Sprachcode. */
$gebiete = ['de' => 'de_DE', 'en' => 'en_US', 'it' => 'it_IT',
            'fr' => 'fr_FR', 'es' => 'es_ES', 'pt' => 'pt_BR'];
$gebiet  = $gebiete[$lang] ?? 'en_US';
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= LightHours\h($titel) ?></title>
<meta name="description" content="<?= LightHours\h($beschreibung) ?>">
<meta name="robots" content="<?= $indexieren ? 'index, follow, max-image-preview:large' : 'noindex, follow' ?>">
<link rel="canonical" href="<?= LightHours\h($kanonisch) ?>">

<?php foreach (I18n::available() as $l): ?>
<link rel="alternate" hreflang="<?= LightHours\h($l) ?>" href="<?= LightHours\h($adresse($l)) ?>">
<?php endforeach; ?>
<?php /* Die parameterlose Adresse verhandelt die Sprache selbst und ist damit
         der richtige Einstieg für Suchmaschinen ohne Sprachpräferenz. */ ?>
<link rel="alternate" hreflang="x-default" href="<?= LightHours\h($basis . '/' . ($seiteName === 'index' ? '' : $seiteName . '.php')) ?>">

<meta name="color-scheme" content="light dark">
<meta name="theme-color" content="#C97B2C">

<link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
<link rel="alternate icon" href="assets/img/favicon.ico" sizes="16x16 32x32 48x48">
<link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
<link rel="manifest" href="site.webmanifest">

<meta property="og:type" content="website">
<meta property="og:site_name" content="lighthours">
<meta property="og:locale" content="<?= LightHours\h($gebiet) ?>">
<?php foreach ($gebiete as $l => $g): ?>
<?php if ($l !== $lang): ?>
<meta property="og:locale:alternate" content="<?= LightHours\h($g) ?>">
<?php endif; ?>
<?php endforeach; ?>
<meta property="og:title" content="<?= LightHours\h($titel) ?>">
<meta property="og:description" content="<?= LightHours\h($beschreibung) ?>">
<meta property="og:url" content="<?= LightHours\h($kanonisch) ?>">
<meta property="og:image" content="<?= LightHours\h($vorschau) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="<?= LightHours\h($i18n->t('meta.og_alt')) ?>">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= LightHours\h($titel) ?>">
<meta name="twitter:description" content="<?= LightHours\h($beschreibung) ?>">
<meta name="twitter:image" content="<?= LightHours\h($vorschau) ?>">

<script>
/* Vor dem ersten Zeichnen ausführen, sonst blitzt der falsche Modus kurz auf. */
try {
  var lhT = localStorage.getItem('lh-theme');
  if (lhT === 'dark' || lhT === 'light') { document.documentElement.setAttribute('data-theme', lhT); }
} catch (e) { /* Speicher gesperrt – dann gilt die Systemvorgabe */ }
</script>

<link rel="preload" href="assets/fonts/outfit-var-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="assets/css/tokens.css">
<link rel="stylesheet" href="assets/css/style.css">

<?php if ($seiteName === 'index'): ?>
<script type="application/ld+json">
<?= json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'WebApplication',
    'name'        => 'lighthours',
    'url'         => $basis . '/',
    'description' => $beschreibung,
    'applicationCategory' => 'UtilitiesApplication',
    'operatingSystem'     => 'Any',
    'browserRequirements' => 'Requires JavaScript',
    'inLanguage'  => I18n::available(),
    'isAccessibleForFree' => true,
    'offers'      => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'EUR'],
    'license'     => 'https://opensource.org/licenses/MIT',
    'featureList' => array_values(array_map(
        static fn(string $k): string => $i18n->t($k),
        ['event.golden_morning', 'event.golden_evening', 'event.blue_morning', 'event.blue_evening']
    )),
] + (LH_AUTHOR !== '' ? ['author' => ['@type' => 'Person', 'name' => LH_AUTHOR]] : []),
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>
<?php endif; ?>
