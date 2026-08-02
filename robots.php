<?php
/**
 * robots.txt, erzeugt aus der Konfiguration.
 *
 * Als PHP statt als feste Datei, damit die Sitemap-Adresse zur jeweiligen
 * Installation passt. In einer festen Datei stünde die Domain des Erstellers –
 * und bliebe in jedem Fork stehen.
 */

declare(strict_types=1);

require_once __DIR__ . '/lib/bootstrap.php';

header('Content-Type: text/plain; charset=utf-8');

$basis = LightHours\base_url();
?>
# lighthours – <?= $basis ?>


User-agent: *
Allow: /

# Kalenderdateien und Schnittstellen gehören nicht in den Suchindex.
# Sie erzeugen bei jedem Abruf Rechenlast und haben keinen Textinhalt.
Disallow: /calendar.php
Disallow: /calendar.ics
Disallow: /api/
Disallow: /check.php

Sitemap: <?= $basis ?>/sitemap.xml
