<?php
/**
 * Gemeinsamer Seitenfuß mit Unterstützungs-Abschnitt.
 *
 * Wird von index.php und check.php eingebunden. Erwartet die Variablen
 * $i18n und $lang aus der aufrufenden Seite.
 *
 * @var LightHours\I18n $i18n
 * @var string          $lang
 */

declare(strict_types=1);
?>

<?php if (LH_COFFEE_USER !== ''): ?>
<section class="support">
  <div class="wrap wrap-narrow">
    <svg class="support-cup" viewBox="0 0 24 24" aria-hidden="true">
      <path d="M4 8h12v7a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V8Z" fill="none" stroke="currentColor"
            stroke-width="1.6" stroke-linejoin="round"/>
      <path d="M16 10h1.8a2.7 2.7 0 0 1 0 5.4H16" fill="none" stroke="currentColor"
            stroke-width="1.6" stroke-linecap="round"/>
      <path d="M7.5 2.5c-.7 1-.7 1.8 0 2.8M11.5 2.5c-.7 1-.7 1.8 0 2.8" fill="none"
            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".6"/>
    </svg>

    <h2><?= $i18n->e('support.title') ?></h2>
    <p><?= $i18n->e('support.text') ?></p>

    <a class="btn btn-primary" href="https://buymeacoffee.com/<?= LightHours\h(LH_COFFEE_USER) ?>"
       target="_blank" rel="noopener">
      <?= $i18n->e('support.button') ?>
    </a>

    <p class="support-note"><?= $i18n->e('support.note') ?></p>
  </div>
</section>
<?php endif; ?>

<footer class="site-footer">
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <strong>lighthours</strong><br>
        <?= $i18n->e('footer.tagline') ?>
      </div>
      <nav class="footer-links" aria-label="<?= $i18n->e('nav.footer') ?>">
        <a href="./?lang=<?= LightHours\h($lang) ?>#generator"><?= $i18n->e('nav.generator') ?></a>
<?php if (LH_SOURCE_URL !== ''): ?>
        <a href="<?= LightHours\h(LH_SOURCE_URL) ?>" rel="noopener"><?= $i18n->e('footer.source') ?></a>
<?php endif; ?>
        <a href="api/times.php?lat=53.5511&amp;lon=9.9937"><?= $i18n->e('footer.api') ?></a>
        <a href="datenschutz.php?lang=<?= LightHours\h($lang) ?>"><?= $i18n->e('footer.privacy') ?></a>
        <a href="impressum.php?lang=<?= LightHours\h($lang) ?>"><?= $i18n->e('footer.imprint') ?></a>
        <a href="https://www.openstreetmap.org/copyright" rel="noopener"><?= $i18n->e('footer.data') ?></a>
      </nav>
    </div>
<?php if (LightHours\Stats::shouldDisplay()): ?>
    <p class="footer-stats">
      <?= $i18n->t('stats.line', ['count' => number_format(
            LightHours\Stats::activeCalendars(), 0,
            $lang === 'en' ? '.' : ',', $lang === 'en' ? ',' : '.')]) ?>
      <span class="footer-stats-note"><?= $i18n->e('stats.note') ?></span>
    </p>
<?php endif; ?>

    <p class="footer-note"><?= $i18n->e('footer.free') ?></p>
  </div>
</footer>
