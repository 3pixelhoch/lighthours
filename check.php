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

<a class="skip-link" href="#ergebnis"><?= e('nav.skip') ?></a>

<header class="site-header">
  <div class="wrap">
    <a class="brand" href="./">
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
          <a href="check.php?lang=<?= LightHours\h($l) ?>" title="<?= LightHours\h(I18n::nativeName($l)) ?>"<?= $l === $lang ? ' aria-current="true"' : '' ?>><?= strtoupper(LightHours\h($l)) ?></a>
        <?php endforeach; ?>
      </nav>
    </div>
  </div>
</header>

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
