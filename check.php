<?php
/**
 * Selbstprüfung nach dem Upload.
 *
 * Im Browser aufrufen: https://deine-domain.de/check.php
 *
 * Prüft alles, was beim ersten Aufsetzen erfahrungsgemäß hakt, und sagt bei
 * jedem Fehlschlag konkret, was zu tun ist.
 *
 * Die Datei darf nach erfolgreicher Prüfung gelöscht werden – für den Betrieb
 * wird sie nicht gebraucht.
 */

declare(strict_types=1);

require_once __DIR__ . '/lib/bootstrap.php';

use LightHours\Geocoder;
use LightHours\I18n;
use LightHours\LightPhases;
use LightHours\Timezone;

$lang = I18n::detect($_GET['lang'] ?? null);
$i18n = new I18n($lang);

function e(string $key, array $vars = []): string
{
    global $i18n;
    return $i18n->e($key, $vars);
}

/** @var array<int, array{0:string,1:bool,2:string,3:string}> */
$ergebnisse = [];

function pruefe(string $name, bool $ok, string $detail = '', string $hilfe = ''): void
{
    global $ergebnisse;
    $ergebnisse[] = [$name, $ok, $detail, $hilfe];
}

// ---------------------------------------------------------------- PHP

pruefe(
    $i18n->t('check.php_version'),
    PHP_VERSION_ID >= 80100,
    PHP_VERSION,
    $i18n->t('check.help_php')
);

foreach (['mbstring', 'json', 'date'] as $ext) {
    $da = extension_loaded($ext);
    pruefe(
        $i18n->t('check.ext', ['name' => $ext]),
        $da,
        $i18n->t($da ? 'check.ext_ok' : 'check.ext_missing'),
        $i18n->t('check.help_ext', ['name' => $ext])
    );
}

$hatCurl  = function_exists('curl_init');
$hatFopen = (bool) ini_get('allow_url_fopen');
pruefe(
    $i18n->t('check.outgoing'),
    $hatCurl || $hatFopen,
    $i18n->t($hatCurl ? 'check.via_curl' : ($hatFopen ? 'check.via_fopen' : 'check.via_none')),
    $i18n->t('check.help_outgoing')
);

// ---------------------------------------------------------------- Konfiguration

$uaOk = LightHours\user_agent_configured();
pruefe(
    $i18n->t('check.contact'),
    $uaOk,
    $uaOk ? LH_USER_AGENT : $i18n->t('check.contact_missing'),
    $i18n->t('check.help_contact')
);

// ---------------------------------------------------------------- Berechnung

try {
    $tz = new DateTimeZone('Europe/Berlin');
    $ph = LightPhases::forDay(new DateTimeImmutable('2026-06-21', $tz), 53.5511, 9.9937, null, $tz);
    $rechnetRichtig = count($ph) === 4;
    $detail = $rechnetRichtig
        ? $i18n->t('check.calc_ok', [
            'time' => (new DateTimeImmutable('@' . $ph[1]['start']))->setTimezone($tz)->format('H:i'),
          ])
        : count($ph) . ' / 4';
} catch (\Throwable $ex) {
    $rechnetRichtig = false;
    $detail = $ex->getMessage();
}
pruefe($i18n->t('check.calc'), $rechnetRichtig, $detail, $i18n->t('check.help_calc'));

pruefe(
    $i18n->t('check.tzdb'),
    Timezone::guess(53.5511, 9.9937, 'DE') === 'Europe/Berlin',
    Timezone::guess(53.5511, 9.9937, 'DE'),
    $i18n->t('check.help_tzdb')
);

// ---------------------------------------------------------------- Ortssuche

if ($uaOk) {
    try {
        // Mit Länderbezug abfragen, sonst steht als erster Treffer die
        // italienische Postleitzahl 20095 – technisch richtig, aber verwirrend.
        $treffer = Geocoder::search('20095', $lang, 3, LightHours\preferred_country() ?: 'DE');
        pruefe(
            $i18n->t('check.geo'),
            $treffer !== [],
            $treffer !== []
                ? $i18n->t('check.geo_ok', ['count' => count($treffer), 'first' => $treffer[0]['short']])
                : $i18n->t('check.geo_none'),
            $i18n->t('check.help_geo_empty')
        );
    } catch (\Throwable $ex) {
        pruefe($i18n->t('check.geo'), false, $ex->getMessage(), $i18n->t('check.help_geo'));
    }
} else {
    pruefe($i18n->t('check.geo'), false, $i18n->t('check.geo_skipped'), '');
}

// ---------------------------------------------------------------- Dateien

$cacheDir = defined('LH_CACHE_DIR') ? LH_CACHE_DIR : '';
$cacheOk  = $cacheDir !== '' && (is_writable($cacheDir) || @mkdir($cacheDir, 0775, true) || is_dir($cacheDir));
pruefe(
    $i18n->t('check.cache'),
    $cacheOk,
    $i18n->t($cacheOk ? 'check.yes' : 'check.cache_no'),
    $i18n->t('check.help_cache')
);

$alleOk = array_reduce($ergebnisse, static fn(bool $c, array $r): bool => $c && $r[1], true);
?>
<!DOCTYPE html>
<html lang="<?= LightHours\h($lang) ?>">
<head>
<?php
$seiteName    = 'check';
$titel        = $i18n->t('check.title') . ' – lighthours';
$beschreibung = $i18n->t('meta.check_desc');
$indexieren   = false;   // Diagnoseseite gehört nicht in Suchergebnisse
require __DIR__ . '/partials/kopf.php';
?>
</head>
<body>

<?php require __DIR__ . '/partials/kopfzeile.php'; ?>

<main class="wrap wrap-narrow" id="ergebnis" style="padding-top:var(--sp-7);padding-bottom:var(--sp-8)">
  <h1><?= e('check.title') ?></h1>
  <p style="color:var(--fg-muted)"><?= e('check.intro') ?></p>

  <ul class="check-list">
    <?php foreach ($ergebnisse as [$name, $ok, $detail, $hilfe]): ?>
    <li>
      <span class="check-state <?= $ok ? 'is-ok' : 'is-fail' ?>" aria-hidden="true"><?= $ok ? '✓' : '✕' ?></span>
      <span>
        <b><?= LightHours\h($name) ?></b>
        <span class="check-detail"><?= LightHours\h($detail) ?></span>
        <?php if (!$ok && $hilfe !== ''): ?>
          <span class="check-help"><?= LightHours\h($hilfe) ?></span>
        <?php endif; ?>
      </span>
    </li>
    <?php endforeach; ?>
  </ul>

  <div class="check-summary <?= $alleOk ? 'is-good' : 'is-bad' ?>">
    <?php if ($alleOk): ?>
      <strong><?= e('check.all_ok') ?></strong>
      <p><a href="./?lang=<?= LightHours\h($lang) ?>"><?= e('check.to_home') ?></a> – <?= e('check.all_ok_text') ?></p>
    <?php else: ?>
      <strong><?= e('check.not_ok') ?></strong>
      <p><?= e('check.not_ok_text') ?></p>
    <?php endif; ?>
  </div>

  <h2 style="margin-top:var(--sp-8);font-size:var(--text-xl)"><?= e('check.manual') ?></h2>
  <p style="color:var(--fg-muted);font-size:var(--text-sm)">
    <a href="lib/config.php">lib/config.php</a> – <?= e('check.manual_text') ?>
  </p>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script>window.LH = { lang: <?= json_encode($lang) ?>, base: <?= json_encode(LightHours\base_url()) ?>, t: {} };</script>
<script src="assets/js/app.js" defer></script>
</body>
</html>
