<?php
/**
 * Startseite und Kalendergenerator.
 */

declare(strict_types=1);

require_once __DIR__ . '/lib/bootstrap.php';

use LightHours\I18n;

$lang = I18n::detect($_GET['lang'] ?? null);
$i18n = new I18n($lang);
$base = LightHours\base_url();


function e(string $key, array $vars = []): string
{
    global $i18n;
    return $i18n->e($key, $vars);
}

/** Text mit erlaubtem einfachem HTML (nur für kuratierte Sprachdateien) */
function raw(string $key, array $vars = []): string
{
    global $i18n;
    return $i18n->t($key, $vars);
}
?>
<!DOCTYPE html>
<html lang="<?= LightHours\h($lang) ?>">
<head>
<?php
$seiteName    = 'index';
$titel        = $i18n->t('meta.title');
$beschreibung = $i18n->t('meta.description');
require __DIR__ . '/partials/kopf.php';
?>
<link rel="stylesheet" href="assets/vendor/maplibre-gl.css">
</head>
<body>

<?php require __DIR__ . '/partials/kopfzeile.php'; ?>

<main>

  <section class="hero">
    <div class="wrap">
      <span class="badge"><?= e('hero.badge') ?></span>
      <h1><?= raw('hero.tagline') ?></h1>
      <p class="lede"><?= e('hero.intro') ?></p>
      <a class="btn btn-primary" href="#generator"><?= e('hero.cta') ?></a>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <h2><?= e('why.title') ?></h2>
      <div class="grid-3">
        <div class="tile tile-golden">
          <h3><?= e('why.golden') ?></h3>
          <p><?= e('why.golden_t') ?></p>
        </div>
        <div class="tile tile-blue">
          <h3><?= e('why.blue') ?></h3>
          <p><?= e('why.blue_t') ?></p>
        </div>
        <div class="tile">
          <h3><?= e('why.short') ?></h3>
          <p><?= e('why.short_t') ?></p>
        </div>
      </div>

      <?php require __DIR__ . '/partials/lichtdiagramm.php'; ?>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <h2><?= e('how.title') ?></h2>
      <div class="grid-3 steps">
        <div class="tile"><h3><?= e('how.step1') ?></h3><p><?= e('how.step1_t') ?></p></div>
        <div class="tile"><h3><?= e('how.step2') ?></h3><p><?= e('how.step2_t') ?></p></div>
        <div class="tile"><h3><?= e('how.step3') ?></h3><p><?= e('how.step3_t') ?></p></div>
      </div>
    </div>
  </section>


  <section class="section screens">
    <div class="wrap">
      <h2><?= e('screens.title') ?></h2>
      <p class="lede-small"><?= e('screens.intro') ?></p>

      <div class="screen-grid">
        <figure class="screen">
          <div class="screen-frame">
            <img class="screen-light" src="assets/img/screens/kalender-woche-hell.webp"
                 width="604" height="420" loading="lazy" decoding="async"
                 alt="<?= e('screens.week_alt') ?>">
            <img class="screen-dark" src="assets/img/screens/kalender-woche-dunkel.webp"
                 width="604" height="420" loading="lazy" decoding="async"
                 alt="<?= e('screens.week_alt') ?>">
          </div>
          <figcaption><?= e('screens.week_cap') ?></figcaption>
        </figure>

        <figure class="screen">
          <div class="screen-frame">
            <img class="screen-light" src="assets/img/screens/kalender-termin-hell.webp"
                 width="604" height="420" loading="lazy" decoding="async"
                 alt="<?= e('screens.event_alt') ?>">
            <img class="screen-dark" src="assets/img/screens/kalender-termin-dunkel.webp"
                 width="604" height="420" loading="lazy" decoding="async"
                 alt="<?= e('screens.event_alt') ?>">
          </div>
          <figcaption><?= e('screens.event_cap') ?></figcaption>
        </figure>
      </div>

      <p class="hint"><?= e('screens.note') ?></p>
    </div>
  </section>

  <!-- ================= Generator ================= -->

  <section class="generator" id="generator">
    <div class="wrap">
      <h2><?= e('gen.title') ?></h2>
      <p class="subtitle"><?= e('gen.subtitle') ?></p>

<?php if (!LightHours\user_agent_configured()): ?>
      <div class="setup-notice" role="alert">
        <strong><?= e('setup.title') ?></strong>
        <p><?= raw('setup.text') ?></p>
        <p><a href="check.php"><?= e('setup.check') ?> →</a></p>
      </div>
<?php endif; ?>

      <!-- Schritt 1: Ort -->
      <div class="step">
        <h3><?= e('gen.step_place') ?></h3>
        <div class="field">
          <label for="q"><?= e('gen.search_label') ?></label>
          <div class="search-row">
            <input type="search" id="q" name="q" placeholder="<?= e('gen.search_ph') ?>"
                   autocomplete="off" enterkeyhint="search" spellcheck="false">
            <button type="button" class="btn btn-primary" id="search-btn"><?= e('gen.search_btn') ?></button>
          </div>
          <ul class="results" id="results" hidden></ul>
          <p class="msg" id="search-msg" role="status" hidden></p>
        </div>
      </div>

      <!-- Schritt 2: Bereich -->
      <div class="step" id="step-area" hidden>
        <h3><?= e('gen.step_area') ?></h3>

        <div class="field">
          <div id="map" role="application" aria-label="Karte"></div>
          <p class="hint"><?= e('gen.map_hint') ?></p>
        </div>

        <div class="options-grid">
          <div class="field">
            <label for="radius"><?= e('gen.radius_label') ?></label>
            <div class="radius-row">
              <select id="radius">
                <option value="25">25 km</option>
                <option value="50" selected>50 km</option>
                <option value="100">100 km</option>
                <option value="150">150 km</option>
                <option value="custom"><?= e('gen.radius_custom') ?></option>
              </select>
              <input type="number" id="radius-custom" min="1" max="500" value="75"
                     aria-label="<?= e('gen.radius_custom') ?>" hidden>
            </div>
          </div>

          <div class="field">
            <label for="tz"><?= e('gen.timezone') ?></label>
            <select id="tz"></select>
            <p class="hint"><?= e('gen.timezone_hint') ?></p>
          </div>
        </div>

        <p class="deviation" id="deviation" role="status"></p>
        <p class="hint"><?= e('gen.radius_why') ?></p>
      </div>

      <!-- Schritt 3: Optionen -->
      <div class="step" id="step-options" hidden>
        <h3><?= e('gen.step_options') ?></h3>

        <div class="field">
          <span class="field-label"><?= e('gen.events_label') ?></span>
          <div class="checks">
            <label class="check"><input type="checkbox" name="events" value="golden_morning" checked>
              <span class="dot dot-gold"></span><?= e('event.golden_morning') ?></label>
            <label class="check"><input type="checkbox" name="events" value="golden_evening" checked>
              <span class="dot dot-gold"></span><?= e('event.golden_evening') ?></label>
            <label class="check"><input type="checkbox" name="events" value="blue_morning" checked>
              <span class="dot dot-blue"></span><?= e('event.blue_morning') ?></label>
            <label class="check"><input type="checkbox" name="events" value="blue_evening" checked>
              <span class="dot dot-blue"></span><?= e('event.blue_evening') ?></label>
          </div>
        </div>

        <div class="options-grid">
          <div class="field">
            <label for="period"><?= e('gen.period_label') ?></label>
            <select id="period">
              <option value="3"><?= e('gen.period_3') ?></option>
              <option value="6"><?= e('gen.period_6') ?></option>
              <option value="12"><?= e('gen.period_12') ?></option>
              <option value="24" selected><?= e('gen.period_24') ?></option>
              <option value="36"><?= e('gen.period_36') ?></option>
              <option value="60"><?= e('gen.period_60') ?></option>
              <option value="custom"><?= e('gen.period_custom') ?></option>
            </select>
            <input type="date" id="end-date" aria-label="<?= e('gen.period_custom') ?>" hidden>
            <p class="hint" id="size-hint" hidden><?= e('gen.size_hint') ?></p>
          </div>

          <div class="field">
            <label for="cal-lang"><?= e('gen.lang_label') ?></label>
            <select id="cal-lang">
              <?php foreach (I18n::available() as $l): ?>
                <option value="<?= LightHours\h($l) ?>"<?= $l === $lang ? ' selected' : '' ?>><?= LightHours\h(I18n::nativeName($l)) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="field">
            <label for="reminder"><?= e('gen.reminder') ?></label>
            <select id="reminder">
              <option value=""><?= e('gen.reminder_none') ?></option>
              <option value="15"><?= e('gen.reminder_15') ?></option>
              <option value="30"><?= e('gen.reminder_30') ?></option>
              <option value="60"><?= e('gen.reminder_60') ?></option>
            </select>
          </div>
        </div>

        <div class="field">
          <label class="check" for="rolling">
            <input type="checkbox" id="rolling" checked>
            <span><?= e('gen.rolling') ?></span>
          </label>
          <p class="hint" id="rolling-hint"></p>
        </div>
      </div>

      <!-- Vorschau -->
      <div class="step" id="step-preview" hidden>
        <h3><?= e('gen.preview') ?></h3>
        <p class="hint"><?= e('gen.preview_hint') ?></p>
        <ul class="preview-list" id="preview"></ul>
      </div>

      <!-- Ausgabe -->
      <div class="step" id="step-output" hidden>
        <h3>Download</h3>
        <div class="actions">
          <a class="btn btn-primary" id="subscribe-btn" href="#"><?= e('gen.subscribe') ?></a>
          <a class="btn btn-outline" id="download-btn" href="#" download><?= e('gen.download') ?></a>
        </div>
        <ul class="action-hints">
          <li><strong><?= e('gen.subscribe') ?>:</strong> <?= e('gen.subscribe_hint') ?></li>
          <li><strong><?= e('gen.download') ?>:</strong> <?= e('gen.download_hint') ?></li>
        </ul>

        <div class="field" style="margin-top:var(--sp-5)">
          <label for="cal-link"><?= e('gen.link_label') ?></label>
          <div class="link-row">
            <input type="text" id="cal-link" readonly>
            <button type="button" class="btn btn-outline" id="copy-btn"
                    data-copy="<?= e('gen.copy') ?>" data-copied="<?= e('gen.copied') ?>"><?= e('gen.copy') ?></button>
          </div>
        </div>

<?php if (LH_MAIL_ENABLED): ?>
        <div class="mail-box">
          <h4><?= e('mail.title') ?></h4>
          <p class="hint"><?= e('mail.hint') ?></p>
          <div class="link-row">
            <input type="email" id="mail-address" placeholder="<?= e('mail.placeholder') ?>"
                   autocomplete="email" aria-label="<?= e('mail.title') ?>">
            <button type="button" class="btn btn-outline" id="mail-btn"
                    data-done="<?= e('mail.sent') ?>" data-failed="<?= e('mail.failed') ?>"><?= e('mail.send') ?></button>
          </div>
          <!-- Für Menschen unsichtbar; füllt es ein Skript aus, verwerfen wir die Anfrage -->
          <input type="text" name="website" id="mail-hp" tabindex="-1" autocomplete="off"
                 aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px">
          <p class="msg" id="mail-msg" role="status" hidden></p>
        </div>
<?php endif; ?>

        <div class="help">
          <h4><?= e('gen.help_title') ?></h4>
          <ul>
            <li><?= raw('gen.help_apple') ?></li>
            <li><?= raw('gen.help_google') ?></li>
            <li><?= raw('gen.help_outlook') ?></li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <div class="grid-3">
        <div class="tile"><h3><?= e('free.title') ?></h3><p><?= e('free.text') ?></p></div>
        <div class="tile">
          <h3><?= e('os.title') ?></h3>
          <p><?= e('os.text') ?></p>
<?php if (LH_SOURCE_URL !== ''): ?>
          <p class="tile-link">
            <a href="<?= LightHours\h(LH_SOURCE_URL) ?>" rel="noopener"><?= e('os.link') ?> →</a>
          </p>
<?php endif; ?>
        </div>
        <div class="tile"><h3><?= e('privacy.title') ?></h3><p><?= e('privacy.text') ?></p></div>
      </div>
    </div>
  </section>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script>
window.LH = {
  lang: <?= json_encode($lang) ?>,
  base: <?= json_encode($base) ?>,
  mail: <?= LH_MAIL_ENABLED ? 'true' : 'false' ?>,
  t: <?= json_encode([
        'noResults' => $i18n->t('gen.no_results'),
        'error'     => $i18n->t('gen.geo_error'),
        'searching' => $i18n->t('gen.searching'),
        'deviation' => $i18n->t('gen.radius_info'),
        'rolling'   => $i18n->t('gen.rolling_hint'),
        'today'     => $i18n->t('gen.today'),
        'tomorrow'  => $i18n->t('gen.tomorrow'),
        'periods'   => [
            '3'  => $i18n->t('gen.period_3'),  '6'  => $i18n->t('gen.period_6'),
            '12' => $i18n->t('gen.period_12'), '24' => $i18n->t('gen.period_24'),
            '36' => $i18n->t('gen.period_36'), '60' => $i18n->t('gen.period_60'),
        ],
      ], JSON_UNESCAPED_UNICODE) ?>
};
</script>
<script src="assets/vendor/maplibre-gl.js" defer></script>
<script src="assets/js/app.js" defer></script>
</body>
</html>
