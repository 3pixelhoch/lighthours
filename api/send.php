<?php
/**
 * POST api/send.php
 *
 * Schickt den persönlichen Kalenderlink an eine freiwillig angegebene Adresse.
 * Es wird nichts gespeichert: keine Adresse, keine Liste, kein Protokoll.
 * Die Nachricht geht raus, danach ist die Adresse wieder vergessen.
 */

declare(strict_types=1);

require_once __DIR__ . '/../lib/bootstrap.php';

use LightHours\I18n;
use LightHours\Mailer;
use LightHours\RateLimit;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    LightHours\json_error('Nur POST.', 405);
}

if (!LH_MAIL_ENABLED) {
    LightHours\json_error('Der E-Mail-Versand ist auf dieser Installation nicht eingeschaltet.', 503);
}

$eingabe = json_decode((string) file_get_contents('php://input'), true);
if (!is_array($eingabe)) {
    $eingabe = $_POST;
}

$lang = (string) ($eingabe['lang'] ?? LH_DEFAULT_LANG);
$i18n = new I18n($lang);
$lang = $i18n->lang();

$mail = trim((string) ($eingabe['email'] ?? ''));
$url  = trim((string) ($eingabe['url'] ?? ''));
$ort  = trim((string) ($eingabe['name'] ?? ''));
$ort  = mb_substr($ort, 0, 120);

// Honigtopf: ein für Menschen unsichtbares Feld. Ist es ausgefüllt, war ein
// Skript am Werk. Wir antworten freundlich, verschicken aber nichts.
if (trim((string) ($eingabe['website'] ?? '')) !== '') {
    LightHours\json_response(['sent' => true]);
}

if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    LightHours\json_error($i18n->t('mail.invalid'), 422);
}

// Der Link muss auf diese Installation zeigen – sonst wäre das Formular ein
// Werkzeug, um beliebige fremde Adressen im eigenen Namen zu verschicken.
$basis = LightHours\base_url();
if ($url === '' || !str_starts_with($url, $basis)) {
    LightHours\json_error('Ungültiger Kalenderlink.', 422);
}

if (!RateLimit::allow('mail', LH_MAIL_MAX_PER_HOUR, 3600)) {
    LightHours\json_error($i18n->t('mail.too_many'), 429);
}

$webcal = preg_replace('#^https?://#', 'webcal://', $url);
$name   = $ort !== '' ? $ort : $i18n->t('mail.your_place');

$text = $i18n->t('mail.body_text', [
    'name'   => $name,
    'url'    => $url,
    'webcal' => $webcal,
]);

$html = '<!DOCTYPE html><html lang="' . LightHours\h($lang) . '"><body style="margin:0;padding:24px;'
      . 'background:#FBFAF8;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,sans-serif;'
      . 'color:#16171B;line-height:1.6">'
      . '<div style="max-width:520px;margin:0 auto;background:#fff;border:1px solid #E7E3DC;'
      . 'border-radius:18px;padding:32px">'
      . '<div style="margin-bottom:24px">'
      . '<span style="display:inline-block;width:44px;height:7px;border-radius:4px;background:#E09A42"></span><br>'
      . '<span style="display:inline-block;width:34px;height:7px;border-radius:4px;background:#C97B2C;margin-top:4px"></span><br>'
      . '<span style="display:inline-block;width:24px;height:7px;border-radius:4px;background:#3C5A8F;margin-top:4px"></span>'
      . '</div>'
      . '<h1 style="font-size:22px;margin:0 0 16px">' . LightHours\h($i18n->t('mail.subject', ['name' => $name])) . '</h1>'
      . '<p style="margin:0 0 20px">' . nl2br(LightHours\h($i18n->t('mail.body_intro', ['name' => $name]))) . '</p>'
      . '<p style="margin:0 0 28px">'
      . '<a href="' . LightHours\h($webcal) . '" style="display:inline-block;background:#E09A42;color:#17130C;'
      . 'text-decoration:none;font-weight:600;padding:13px 26px;border-radius:999px">'
      . LightHours\h($i18n->t('gen.subscribe')) . '</a></p>'
      . '<p style="margin:0 0 8px;font-size:14px;color:#6C7076">' . LightHours\h($i18n->t('mail.link_label')) . '</p>'
      . '<p style="margin:0 0 28px;font-size:13px;word-break:break-all">'
      . '<a href="' . LightHours\h($url) . '" style="color:#A25F1E">' . LightHours\h($url) . '</a></p>'
      . '<h2 style="font-size:16px;margin:0 0 12px">' . LightHours\h($i18n->t('gen.help_title')) . '</h2>'
      . '<ul style="margin:0 0 24px;padding-left:20px;font-size:14px;color:#3E4148">'
      . '<li style="margin-bottom:8px">' . $i18n->t('gen.help_apple') . '</li>'
      . '<li style="margin-bottom:8px">' . $i18n->t('gen.help_google') . '</li>'
      . '<li>' . $i18n->t('gen.help_outlook') . '</li>'
      . '</ul>'
      . '<p style="margin:0;font-size:13px;color:#6C7076;border-top:1px solid #E7E3DC;padding-top:16px">'
      . LightHours\h($i18n->t('mail.footer')) . '</p>'
      . '</div></body></html>';

try {
    Mailer::send($mail, $i18n->t('mail.subject', ['name' => $name]), $text, $html);
} catch (\Throwable $e) {
    // Die technische Ursache gehört ins Serverprotokoll, nicht an den Besucher.
    error_log('lighthours Mailversand: ' . $e->getMessage());
    LightHours\json_error($i18n->t('mail.failed'), 502);
}

LightHours\json_response(['sent' => true]);
