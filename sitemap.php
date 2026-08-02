<?php
/**
 * Erzeugt die Sitemap aus dem tatsächlichen Sprachbestand.
 *
 * Als PHP statt als feste Datei, damit eine neue Sprache automatisch
 * auftaucht, statt in Vergessenheit zu geraten.
 */

declare(strict_types=1);

require_once __DIR__ . '/lib/bootstrap.php';

use LightHours\I18n;

$basis   = LightHours\base_url();
$sprachen = I18n::available();

/** Seiten mit ihrer Gewichtung und Änderungshäufigkeit */
$seiten = [
    ''                 => ['1.0', 'weekly'],
    'datenschutz.php'  => ['0.3', 'yearly'],
    'impressum.php'    => ['0.3', 'yearly'],
];

// Jüngste Änderung an den Inhalten als Datum verwenden
$stand = 0;
foreach (array_merge(glob(__DIR__ . '/lang/*.php') ?: [], [__DIR__ . '/index.php']) as $datei) {
    $stand = max($stand, (int) filemtime($datei));
}

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
<?php foreach ($seiten as $datei => [$gewicht, $haeufigkeit]): ?>
<?php foreach ($sprachen as $lang): ?>
  <url>
    <loc><?= LightHours\h($basis . '/' . $datei . '?lang=' . $lang) ?></loc>
    <lastmod><?= date('Y-m-d', $stand) ?></lastmod>
    <changefreq><?= $haeufigkeit ?></changefreq>
    <priority><?= $gewicht ?></priority>
<?php foreach ($sprachen as $andere): ?>
    <xhtml:link rel="alternate" hreflang="<?= LightHours\h($andere) ?>" href="<?= LightHours\h($basis . '/' . $datei . '?lang=' . $andere) ?>"/>
<?php endforeach; ?>
    <xhtml:link rel="alternate" hreflang="x-default" href="<?= LightHours\h($basis . '/' . $datei) ?>"/>
  </url>
<?php endforeach; ?>
<?php endforeach; ?>
</urlset>
