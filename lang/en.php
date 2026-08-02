<?php
/**
 * English strings. Also used as fallback when a key is missing in another language.
 */

declare(strict_types=1);

return [
    // Calendar
    'event.golden_morning' => 'Golden Hour (Morning)',
    'event.golden_evening' => 'Golden Hour (Evening)',
    'event.blue_morning'   => 'Blue Hour (Morning)',
    'event.blue_evening'   => 'Blue Hour (Evening)',
    'cal.name'             => 'lighthours – {name}',
    'cal.description'      => 'Golden and blue hour times for {name}. Created with lighthours.',
    'cal.event_description'=> "{event} in {name}\nStarts: {start}\nEnds: {end}\n\nCreated with lighthours – light planning for photography and film.",

    // Head
    'meta.title'       => 'lighthours – Golden & Blue Hour as a Calendar Subscription',
    'meta.description' => 'Free calendar with daily golden hour and blue hour times for any place on earth. Subscribe in Apple Calendar, Google Calendar or Outlook. Open source.',
    'nav.skip'         => 'Skip to content',

    // Home
    'hero.tagline'  => 'The best light.<br>Right in your calendar.',
    'hero.intro'    => 'lighthours calculates the daily golden hour and blue hour times for any place on earth – and delivers them as a calendar you subscribe to once and never think about again.',
    'hero.cta'      => 'Create calendar',
    'hero.badge'    => 'Free · No account · Open source',

    'why.title'     => 'Why these hours matter',
    'why.golden'    => 'Golden hour',
    'why.golden_t'  => 'Just after sunrise and before sunset the sun sits low. Light turns soft, warm and directional – skin tones flatter, landscapes gain depth, harsh shadows disappear.',
    'why.blue'      => 'Blue hour',
    'why.blue_t'    => 'Before and after that comes twilight: the sky glows deep blue while artificial light and remaining daylight balance out. The time for cities, architecture and mood.',
    'why.short'     => 'Short',
    'why.short_t'   => 'Together they rarely last more than an hour, and they shift every day. Having them in your calendar means planning shoots realistically – instead of arriving on set to find the light already gone.',

    'how.title'   => 'How it works',
    'how.step1'   => 'Enter a place',
    'how.step1_t' => 'A city, address or postcode is enough. You never have to look up coordinates.',
    'how.step2'   => 'Pick your options',
    'how.step2_t' => 'Which light phases, which period, which reminder. Fine-tune the center on the map.',
    'how.step3'   => 'Subscribe',
    'how.step3_t' => 'One click and the events are in your calendar. With a subscription they keep themselves up to date.',

    'free.title' => 'Why free?',
    'free.text'  => 'The calculation costs nothing but a little compute, and nobody owns the position of the sun. There are no accounts, no ads, no tracking and no paywall – not later either.',
    'os.title'   => 'Why open source?',
    'os.text'    => 'The complete source code is open and free to use. Run lighthours on your own webspace if you like. No service that disappears one day and takes your calendars with it.',
    'privacy.title' => 'What about data?',
    'privacy.text'  => 'No cookies, no trackers, no stored visits. The only thing kept is your colour mode choice, stored locally in your browser. Fonts are served from this server and place lookups run through the backend, so your search terms reach OpenStreetMap without your IP address. One exception: map tiles are loaded by your browser directly from openstreetmap.org once you have picked a place. The number of active calendars is counted anonymously: only a hash of the calendar settings is stored, without IP address and without a timestamp.',

    // Generator
    'gen.title'    => 'Create calendar',
    'gen.subtitle' => 'Three steps – no account, no email address.',

    'gen.step_place'   => 'Place',
    'gen.search_label' => 'City, address or postcode',
    'gen.search_ph'    => 'e.g. Hamburg, 20095 or Elbstrand',
    'gen.search_btn'   => 'Search',
    'gen.searching'    => 'Searching …',
    'gen.no_results'   => 'Nothing found for that. Try a larger town nearby.',
    'gen.geo_error'    => 'The place search is not responding right now. Please try again in a moment.',

    'gen.step_area'    => 'Area',
    'gen.map_hint'     => 'Drag the marker or click the map to adjust the center.',
    'gen.radius_label' => 'Radius',
    'gen.radius_custom'=> 'Custom radius',
    'gen.radius_info'  => 'Within this area light times differ by at most about <strong>{minutes} minutes</strong>.',
    'gen.radius_why'   => 'So you do not need a separate calendar per location: one calendar covers your whole region.',
    'gen.timezone'     => 'Time zone',
    'gen.timezone_hint'=> 'Events appear in the local time of this place.',

    'gen.step_options' => 'Options',
    'gen.events_label' => 'Which events?',
    'gen.period_label' => 'Period',
    'gen.period_3'     => '3 months',
    'gen.period_6'     => '6 months',
    'gen.period_12'    => '1 year',
    'gen.period_24'    => '2 years',
    'gen.period_36'    => '3 years',
    'gen.period_60'    => '5 years',
    'gen.period_custom'=> 'Custom end date',
    'gen.rolling'      => 'Rolling subscription',
    'gen.rolling_hint' => 'The calendar moves with you: it always holds the next {months}, with nothing to do on your side.',
    'gen.lang_label'   => 'Event language',
    'gen.reminder'     => 'Reminder',
    'gen.reminder_none'=> 'None',
    'gen.reminder_15'  => '15 minutes before',
    'gen.reminder_30'  => '30 minutes before',
    'gen.reminder_60'  => '60 minutes before',

    'gen.preview'      => 'Preview',
    'gen.preview_hint' => 'The next events at this place:',
    'gen.today'        => 'Today',
    'gen.tomorrow'     => 'Tomorrow',

    'gen.subscribe'    => 'Subscribe to calendar',
    'gen.subscribe_hint'=> 'Opens your calendar app. Events stay up to date by themselves.',
    'gen.download'     => 'Download ICS',
    'gen.download_hint'=> 'A one-off file to import – without updates.',
    'gen.link_label'   => 'Or copy the subscription link',
    'gen.copy'         => 'Copy',
    'gen.copied'       => 'Copied',

    'gen.help_title'   => 'Setting up the subscription',
    'gen.help_apple'   => '<strong>Apple Calendar:</strong> Tap “Subscribe to calendar” – everything else happens automatically.',
    'gen.help_google'  => '<strong>Google Calendar:</strong> Copy the link, then add it under “Add calendar → From URL”.',
    'gen.help_outlook' => '<strong>Outlook:</strong> Copy the link, then “Add calendar → Subscribe from web”.',

    // Footer
    'footer.tagline' => 'Light planning for photography and film.',
    'footer.source'  => 'Source code',
    'footer.api'     => 'API',
    'footer.privacy' => 'Privacy',
    'footer.data'    => 'Place data from OpenStreetMap',
    'footer.free'    => 'Free to use under the MIT licence.',

    // Colour mode
    'theme.auto' => 'Mode: system',
    'theme.light' => 'Mode: light',
    'theme.dark' => 'Mode: dark',

    // Setup
    'setup.title' => 'Setup not finished yet',
    'setup.text' => 'In <code>lib/config.php</code>, <code>LH_USER_AGENT</code> still holds the placeholder address. OpenStreetMap rejects requests like that, so the place search cannot work. Put a real contact address there and it works right away.',
    'setup.check' => 'Open the full check',

    // Navigation, locations, email, support
    'nav.language' => 'Language',
    'nav.footer' => 'More pages',
    'nav.generator' => 'Calendar',
    'mail.title' => 'Send the link by email',
    'mail.hint' => 'Optional. The address is used for this one message and stored nowhere.',
    'mail.placeholder' => 'you@example.com',
    'mail.send' => 'Send',
    'mail.sent' => 'Sent – check your inbox.',
    'mail.failed' => 'Sending failed. Try again later or copy the link instead.',
    'mail.invalid' => 'That email address does not look right.',
    'mail.too_many' => 'Too many messages in a short time. Please try again later.',
    'mail.your_place' => 'your location',
    'mail.subject' => 'Your lighthours calendar for {name}',
    'mail.body_intro' => "Here is your personal calendar for {name}.\n\nOnce subscribed it keeps itself up to date – you never have to think about it again.",
    'mail.link_label' => 'If the button does not work, here is the link to copy:',
    'mail.footer' => 'This message was sent once at your request. Your address was not stored and you will not hear from us again.',
    'mail.body_text' => "Here is your personal lighthours calendar for {name}.\n\nSubscribe to the calendar:\n{webcal}\n\nOr add this link in your calendar app:\n{url}\n\nHow to do it:\n- Apple Calendar: open the link, everything else happens automatically.\n- Google Calendar: Settings, then Add calendar, then From URL.\n- Outlook: Add calendar, then Subscribe from web.\n\nThis message was sent once at your request. Your address was not stored.",
    'support.title' => 'Do you like lighthours?',
    'support.text' => 'The project is free and stays free. No ads, no accounts, no paywall. If it helps you, you can buy a coffee – but there is no need.',
    'support.button' => 'Buy me a coffee',
    'support.note' => 'Leads to Buy Me a Coffee. No script from there is embedded – only your click leaves this page.',

    // Self-check
    'check.title' => 'Self-check',
    'check.intro' => 'This page checks whether everything is in place on this server. Once every line shows a tick you can delete check.php.',
    'check.php_version' => 'PHP version',
    'check.ext' => 'Extension {name}',
    'check.ext_ok' => 'present',
    'check.ext_missing' => 'missing',
    'check.outgoing' => 'Outgoing connections possible',
    'check.via_curl' => 'via cURL',
    'check.via_fopen' => 'via allow_url_fopen',
    'check.via_none' => 'neither cURL nor allow_url_fopen',
    'check.contact' => 'Contact address configured',
    'check.contact_missing' => 'still the placeholder address',
    'check.calc' => 'Astronomical calculation',
    'check.calc_ok' => 'Golden hour on 21 June in Hamburg: {time}',
    'check.tzdb' => 'Time zone database',
    'check.geo' => 'Place search (test with postcode 20095)',
    'check.geo_ok' => '{count} results, first: {first}',
    'check.geo_none' => 'no result',
    'check.geo_skipped' => 'skipped – enter the contact address first',
    'check.cache' => 'Cache writable',
    'check.yes' => 'yes',
    'check.cache_no' => 'no – works without it, just slower',
    'check.help_php' => 'lighthours needs PHP 8.1 or newer. Change it in your hosting panel under “PHP settings”.',
    'check.help_ext' => 'The {name} extension must be enabled. Ask your host.',
    'check.help_outgoing' => 'Without one of the two, the place search cannot work.',
    'check.help_contact' => 'In lib/config.php set LH_USER_AGENT to a real address, for example: lighthours/1.0 (+https://your-subdomain.com; you@your-domain.com). OpenStreetMap rejects requests with placeholders using HTTP 403 – by far the most common reason the search finds nothing.',
    'check.help_calc' => 'Please upload all files again.',
    'check.help_tzdb' => 'The PHP time zone database appears incomplete. Report it to your host.',
    'check.help_geo' => 'Can the server reach nominatim.openstreetmap.org? Some hosts block outgoing connections until you ask them to open them.',
    'check.help_geo_empty' => 'The request went through but returned nothing. Please try again later.',
    'check.help_cache' => 'Optional. A writable cache/ directory takes load off OpenStreetMap.',
    'check.all_ok' => 'Everything ready.',
    'check.all_ok_text' => 'Go to the home page – and check.php can go.',
    'check.not_ok' => 'Something is still missing.',
    'check.not_ok_text' => 'Each note above names the next step. After a change, simply reload this page.',
    'check.manual' => 'Still to check by hand',
    'check.manual_text' => 'Open lib/config.php. It must return a 403 error. If the file is shown or downloaded instead, your host does not evaluate .htaccess – then block the directories lib, lang, data, partials and cache through your hosting panel.',
    'check.to_home' => 'Go to the home page',
    'gen.size_hint' => 'Very long periods produce large files – five years with every event type is around 3 MB. Some calendar apps load that noticeably slowly. Two years as a rolling subscription is usually the better choice.',

    // Eigenbezeichnung der Sprache – erscheint in der Auswahl
    'lang.name' => 'English',

    // Screenshots
    'screens.title' => 'And this is how it looks in your calendar',
    'screens.intro' => 'No app, no account – the events simply appear where you already look.',
    'screens.week_alt' => 'Two consecutive days in week view, each with a golden hour event',
    'screens.week_cap' => 'Two consecutive days in Berlin: 18:52, then 18:54. Golden hour shifts by a minute or two every day – nobody keeps that in their head.',
    'screens.event_alt' => 'An opened event showing place, time and description',
    'screens.event_cap' => 'Every event states start, end and place. Language and description come from the settings you chose when creating it.',
    'screens.note' => 'Screenshots from Apple Calendar. The same events appear in Google Calendar and Outlook.',

    // Anonyme Zählung
    'stats.line' => 'lighthours currently keeps <strong>{count} calendars</strong> up to date.',
    'stats.note' => 'This counts subscribed calendars, not people – anyone planning several regions appears more than once. No IP address and no browser identifier is stored, only the fact that a calendar was fetched.',

    // Rechtstexte
    'legal.privacy_title' => 'Privacy',
    'legal.imprint_title' => 'Legal notice',
    'legal.updated' => 'Last updated: {date}',
    'legal.only_de_en' => 'This page is only available in German and English. The German version is authoritative.',
    'footer.imprint' => 'Legal notice',

    // Suchmaschinen und soziale Netzwerke
    'meta.og_alt' => 'lighthours – golden and blue hour as a calendar subscription',
    'meta.privacy_desc' => 'What lighthours stores and what it does not: no cookies, no accounts, no advertising networks. Full privacy statement.',
    'meta.imprint_desc' => 'Provider identification and contact for lighthours, the open source calendar for golden and blue hour.',
    'meta.check_desc' => 'Self-check of the installation.',
    'os.link' => 'Source code on GitHub',
];
