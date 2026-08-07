# Changelog

Format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
versioning follows [Semantic Versioning](https://semver.org/).

[Deutsche Fassung](CHANGELOG.de.md)

## [1.1.0] – 2026-08-07

### Calendar

- Golden hour events name the stretch where the sun is above the horizon –
  only there does the light come from the side and model
- Weekdays can be restricted: a two-year subscription drops from 2928 events
  to a few hundred
- A second, earlier reminder for preparation, deliberately on golden hours
  only, so it does not become eight alerts a day
- Every event states sunrise or sunset as a reference point

### Website

- A diagram on the home page explains why the golden hour only ends after
  sunset
- A visible way to report times that look wrong

### Fixed

- On the legal pages the logo linked to the page you were already on instead
  of the home page
- Without a country code the timezone lookup returned "UTC" instead of
  "Atlantic/Reykjavik" – correct in time, wrong as a calendar label
- The API printed coordinates with seventeen decimal places
- On phones the search field and button sat side by side instead of stacked

## [1.0.0] – 2026-08-02

First complete release. Runs on any web space with PHP 8.1: upload, set one
line in `lib/config.php`, done. No database, no Composer, no dependencies.

### Calendar

- Golden and blue hour, morning and evening, for any place on earth
- Subscribable iCal feed and ICS download
- Period from three months to five years, or a custom end date
- Rolling subscription that moves with the date and never runs empty
- Optional reminder 15, 30 or 60 minutes before a phase starts
- Proper `VTIMEZONE` including daylight saving transitions
- Stable event UIDs, so calendar apps don't create duplicates
- Every event states sunrise or sunset as a reference point: the evening
  golden hour only ends about 25 minutes after the sun has set

### Calculation

- Sun position using the full NOAA algorithm with atmospheric refraction —
  calculated from the apparent solar altitude
- Verified against an independent implementation: across a full year at six
  locations from Reykjavík to Sydney, mean deviation 0.9 seconds, worst case 3
- Events anchored to the local date, not the UTC date
- Near the poles, non-existent phases are omitted instead of given an invented
  time

### Place search

- Enter a city, address, region or postcode — never coordinates
- Time zone derived from the place; zones with identical behaviour collapse to
  the common spelling
- Ambiguous postcodes weighted by the visitor's likely country
- Runs server-side so visitor IP addresses never reach OpenStreetMap; results
  cached for seven days

### Interface

- Map with draggable centre and selectable validity radius
- Shows the maximum time deviation within the chosen radius
- Preview of the next events before subscribing
- Light and dark mode, following the system or set manually
- Six languages: German, English, Italian, French, Spanish, Portuguese —
  including the calendar events themselves

### Privacy

- No cookies, no accounts, no advertising networks, no tracking
- Fonts and map library self-hosted; nothing loads from Google or a CDN
- Map tiles loaded only after a place has been selected
- Anonymous count of active calendars via a hash of the calendar settings,
  without IP address and without a timestamp
- Privacy statement and legal notice as separate pages

### Operation

- `check.php` verifies the installation and names what is missing
- Clear message while the contact address for the place search is unset — the
  most common stumbling block on first setup
- Optional email delivery of the subscription link via SMTP or `mail()`, off by
  default, with an abuse throttle
- 163 tests without a framework: `php tests/run.php`

### Search engines

- Canonical URLs, language alternates and `x-default`
- Social preview images in all six languages
- Structured data as `WebApplication`
- `robots.txt` and a sitemap that picks up new languages by itself

### Design

- Mark of three stacked bars: the horizon as bands of light
- Outfit as the single typeface, variable and self-hosted (45 KB)
- Every colour, size and spacing value in `assets/css/tokens.css`
- All contrast ratios meet WCAG AA in both modes
