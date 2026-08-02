<?php
/**
 * Textes français.
 *
 * Pour ajouter une langue : copier ce fichier, traduire les valeurs et
 * l'enregistrer sous <code>.php. La langue apparaît automatiquement dans le
 * sélecteur.
 */

declare(strict_types=1);

return [
    // Calendrier
    'event.golden_morning' => 'Heure dorée (matin)',
    'event.golden_evening' => 'Heure dorée (soir)',
    'event.blue_morning'   => 'Heure bleue (matin)',
    'event.blue_evening'   => 'Heure bleue (soir)',
    'cal.name'             => 'lighthours – {name}',
    'cal.description'      => 'Heure dorée et heure bleue pour {name}. Créé avec lighthours.',
    'cal.event_description' => "{event} à {name}\nDébut : {start}\nFin : {end}\n\nCréé avec lighthours – planification de la lumière pour la photo et la vidéo.",

    // En-tête
    'meta.title'       => 'lighthours – Heure dorée et heure bleue en abonnement calendrier',
    'meta.description' => 'Calendrier gratuit avec les horaires quotidiens de l\'heure dorée et de l\'heure bleue pour n\'importe quel lieu du monde. Compatible Apple Calendrier, Google Agenda et Outlook. Open source.',
    'nav.skip'         => 'Aller au contenu',

    // Accueil
    'hero.tagline'  => 'La plus belle lumière.<br>Directement dans votre agenda.',
    'hero.intro'    => 'lighthours calcule les horaires quotidiens de l\'heure dorée et de l\'heure bleue pour n\'importe quel lieu du monde et les livre sous forme de calendrier : on s\'y abonne une fois et on n\'y pense plus.',
    'hero.cta'      => 'Créer le calendrier',
    'hero.badge'    => 'Gratuit · Sans compte · Open source',

    'why.title'     => 'Pourquoi ces heures comptent',
    'why.golden'    => 'Heure dorée',
    'why.golden_t'  => 'Juste après le lever et avant le coucher du soleil, l\'astre est bas. La lumière devient douce, chaude et directionnelle : les carnations sont flattées, les paysages gagnent en profondeur, les ombres dures disparaissent.',
    'why.blue'      => 'Heure bleue',
    'why.blue_t'    => 'Avant et après vient le crépuscule : le ciel prend un bleu profond tandis que lumière artificielle et lumière résiduelle s\'équilibrent. C\'est le moment de la ville, de l\'architecture et de l\'ambiance.',
    'why.short'     => 'Court',
    'why.short_t'   => 'Ensemble, elles dépassent rarement une heure et se décalent chaque jour. Les avoir dans son agenda, c\'est planifier de façon réaliste au lieu de constater sur place que la lumière est déjà partie.',

    'how.title'   => 'Comment ça marche',
    'how.step1'   => 'Saisir un lieu',
    'how.step1_t' => 'Une ville, une adresse ou un code postal suffisent. Aucune coordonnée à chercher.',
    'how.step2'   => 'Choisir les options',
    'how.step2_t' => 'Quelles phases de lumière, quelle période, quel rappel. Sur la carte, vous ajustez le centre.',
    'how.step3'   => 'S\'abonner',
    'how.step3_t' => 'Un clic et les événements sont dans votre agenda. Avec l\'abonnement, ils se mettent à jour seuls.',

    'free.title' => 'Pourquoi gratuit ?',
    'free.text'  => 'Le calcul ne coûte qu\'un peu de temps machine, et la position du soleil n\'appartient à personne. Pas de compte, pas de publicité, pas de pistage et aucun péage – maintenant comme plus tard.',
    'os.title'   => 'Pourquoi open source ?',
    'os.text'    => 'Le code source est consultable et librement réutilisable. Qui le souhaite héberge lighthours sur son propre espace web. Pas de service qui ferme un jour en emportant vos calendriers.',
    'privacy.title' => 'Et les données ?',
    'privacy.text'  => 'Aucun cookie, aucun traceur, aucune visite enregistrée. Seul votre choix de mode d\'affichage est conservé, localement dans le navigateur. Les polices sont hébergées sur ce serveur et la recherche de lieux passe par le backend : vos termes de recherche atteignent OpenStreetMap sans votre adresse IP. Seule exception : les tuiles de carte sont chargées par votre navigateur directement depuis openstreetmap.org, une fois un lieu choisi. Le nombre d\'agendas actifs est compté de façon anonyme : seul un hachage des réglages de l\'agenda est conservé, sans adresse IP ni horodatage.',

    // Générateur
    'gen.title'    => 'Créer le calendrier',
    'gen.subtitle' => 'Trois étapes – sans compte, sans adresse e-mail.',

    'gen.step_place'   => 'Lieu',
    'gen.search_label' => 'Ville, adresse ou code postal',
    'gen.search_ph'    => 'p. ex. Lyon, 69001 ou Croix-Rousse',
    'gen.search_btn'   => 'Rechercher',
    'gen.searching'    => 'Recherche en cours …',
    'gen.no_results'   => 'Aucun résultat. Essayez une ville plus grande à proximité.',
    'gen.geo_error'    => 'La recherche de lieux ne répond pas pour le moment. Réessayez dans un instant.',

    'gen.step_area'    => 'Zone',
    'gen.map_hint'     => 'Déplacez le repère ou touchez la carte pour ajuster le centre.',
    'gen.radius_label' => 'Rayon',
    'gen.radius_custom' => 'Rayon personnalisé',
    'gen.radius_info'  => 'Dans cette zone, les horaires de lumière varient d\'environ <strong>{minutes} minutes</strong> au maximum.',
    'gen.radius_why'   => 'Inutile donc d\'avoir un calendrier par lieu de prise de vue : un seul couvre toute votre région.',
    'gen.timezone'     => 'Fuseau horaire',
    'gen.timezone_hint' => 'Les événements apparaissent à l\'heure locale de ce lieu.',

    'gen.step_options' => 'Options',
    'gen.events_label' => 'Quels événements ?',
    'gen.period_label' => 'Période',
    'gen.period_3'     => '3 mois',
    'gen.period_6'     => '6 mois',
    'gen.period_12'    => '1 an',
    'gen.period_24'    => '2 ans',
    'gen.period_36'    => '3 ans',
    'gen.period_60'    => '5 ans',
    'gen.period_custom' => 'Date de fin personnalisée',
    'gen.rolling'      => 'Abonnement glissant',
    'gen.rolling_hint' => 'Le calendrier avance avec vous : il contient toujours {months} à partir d\'aujourd\'hui, sans rien faire de votre côté.',
    'gen.lang_label'   => 'Langue des événements',
    'gen.reminder'     => 'Rappel',
    'gen.reminder_none' => 'Aucun',
    'gen.reminder_15'  => '15 minutes avant',
    'gen.reminder_30'  => '30 minutes avant',
    'gen.reminder_60'  => '60 minutes avant',

    'gen.preview'      => 'Aperçu',
    'gen.preview_hint' => 'Les prochains événements à cet endroit :',
    'gen.today'        => 'Aujourd\'hui',
    'gen.tomorrow'     => 'Demain',

    'gen.subscribe'    => 'S\'abonner au calendrier',
    'gen.subscribe_hint' => 'Ouvre votre application d\'agenda. Les événements resteront à jour tout seuls.',
    'gen.download'     => 'Télécharger l\'ICS',
    'gen.download_hint' => 'Un fichier unique à importer, sans mise à jour.',
    'gen.link_label'   => 'Ou copier le lien d\'abonnement',
    'gen.copy'         => 'Copier',
    'gen.copied'       => 'Copié',

    'gen.help_title'   => 'Mettre en place l\'abonnement',
    'gen.help_apple'   => '<strong>Apple Calendrier :</strong> touchez « S\'abonner au calendrier » – le reste est automatique.',
    'gen.help_google'  => '<strong>Google Agenda :</strong> copiez le lien, puis ajoutez-le sous « Ajouter un agenda → À partir de l\'URL ».',
    'gen.help_outlook' => '<strong>Outlook :</strong> copiez le lien, puis « Ajouter un calendrier → S\'abonner à partir du web ».',

    // Pied de page
    'footer.tagline' => 'Planification de la lumière pour la photo et la vidéo.',
    'footer.source'  => 'Code source',
    'footer.api'     => 'API',
    'footer.privacy' => 'Confidentialité',
    'footer.data'    => 'Données des lieux : OpenStreetMap',
    'footer.free'    => 'Librement utilisable sous licence MIT.',

    // Mode d'affichage
    'theme.auto'  => 'Mode : système',
    'theme.light' => 'Mode : clair',
    'theme.dark'  => 'Mode : sombre',

    // Configuration
    'setup.title' => 'Configuration pas encore terminée',
    'setup.text'  => 'Dans <code>lib/config.php</code>, <code>LH_USER_AGENT</code> contient encore l\'adresse d\'exemple. OpenStreetMap refuse ce type de requêtes, la recherche de lieux ne peut donc pas fonctionner. Indiquez une véritable adresse de contact et tout fonctionnera aussitôt.',
    'setup.check' => 'Ouvrir la vérification complète',

    // Navigation, lieux, e-mail, soutien
    'nav.language'  => 'Langue',
    'nav.footer'    => 'Autres pages',
    'nav.generator' => 'Calendrier',

    'mail.title'       => 'Envoyer le lien par e-mail',
    'mail.hint'        => 'Facultatif. L\'adresse ne sert qu\'à ce message et n\'est conservée nulle part.',
    'mail.placeholder' => 'vous@exemple.fr',
    'mail.send'        => 'Envoyer',
    'mail.sent'        => 'Envoyé – regardez votre boîte de réception.',
    'mail.failed'      => 'L\'envoi a échoué. Réessayez plus tard ou copiez le lien.',
    'mail.invalid'     => 'Cette adresse e-mail ne semble pas correcte.',
    'mail.too_many'    => 'Trop de messages en peu de temps. Réessayez plus tard.',
    'mail.your_place'  => 'votre lieu',
    'mail.subject'     => 'Votre calendrier lighthours pour {name}',
    'mail.body_intro'  => "Voici votre calendrier personnel pour {name}.\n\nUne fois abonné, il se met à jour tout seul : vous n\'aurez plus à y penser.",
    'mail.link_label'  => 'Si le bouton ne fonctionne pas, voici le lien à copier :',
    'mail.footer'      => 'Ce message a été envoyé une seule fois à votre demande. Votre adresse n\'a pas été conservée et vous ne recevrez rien d\'autre.',
    'mail.body_text'   => "Voici votre calendrier lighthours personnel pour {name}.\n\nS\'abonner au calendrier :\n{webcal}\n\nOu ajoutez ce lien dans votre application d\'agenda :\n{url}\n\nComment faire :\n- Apple Calendrier : ouvrez le lien, le reste est automatique.\n- Google Agenda : Paramètres, puis Ajouter un agenda, puis À partir de l\'URL.\n- Outlook : Ajouter un calendrier, puis S\'abonner à partir du web.\n\nCe message a été envoyé une seule fois à votre demande. Votre adresse n\'a pas été conservée.",

    'support.title'  => 'lighthours vous plaît ?',
    'support.text'   => 'Le projet est gratuit et le restera. Pas de publicité, pas de compte, pas de péage. S\'il vous rend service, vous pouvez offrir un café – rien ne vous y oblige.',
    'support.button' => 'Offrir un café',
    'support.note'   => 'Mène à Buy Me a Coffee. Aucun script de ce site n\'est intégré : seul votre clic quitte cette page.',

    // Self-check
    'check.title' => 'Vérification',
    'check.intro' => 'Cette page vérifie que tout est en place sur ce serveur. Quand chaque ligne affiche une coche, vous pouvez supprimer check.php.',
    'check.php_version' => 'Version de PHP',
    'check.ext' => 'Extension {name}',
    'check.ext_ok' => 'présente',
    'check.ext_missing' => 'absente',
    'check.outgoing' => 'Connexions sortantes possibles',
    'check.via_curl' => 'via cURL',
    'check.via_fopen' => 'via allow_url_fopen',
    'check.via_none' => 'ni cURL ni allow_url_fopen',
    'check.contact' => 'Adresse de contact renseignée',
    'check.contact_missing' => 'encore l\'adresse d\'exemple',
    'check.calc' => 'Calcul astronomique',
    'check.calc_ok' => 'Heure dorée le 21 juin à Hambourg : {time}',
    'check.tzdb' => 'Base des fuseaux horaires',
    'check.geo' => 'Recherche de lieux (test avec le code postal 20095)',
    'check.geo_ok' => '{count} résultats, premier : {first}',
    'check.geo_none' => 'aucun résultat',
    'check.geo_skipped' => 'ignorée – renseignez d\'abord l\'adresse de contact',
    'check.cache' => 'Cache accessible en écriture',
    'check.yes' => 'oui',
    'check.cache_no' => 'non – fonctionne quand même, simplement plus lentement',
    'check.help_php' => 'lighthours nécessite PHP 8.1 ou plus récent. À changer dans le panneau d\'hébergement sous « Paramètres PHP ».',
    'check.help_ext' => 'L\'extension {name} doit être activée. Demandez à votre hébergeur.',
    'check.help_outgoing' => 'Sans l\'une des deux possibilités, la recherche de lieux ne peut pas fonctionner.',
    'check.help_contact' => 'Dans lib/config.php, indiquez une adresse réelle pour LH_USER_AGENT, par exemple : lighthours/1.0 (+https://votre-sous-domaine.fr ; vous@votre-domaine.fr). OpenStreetMap refuse les requêtes avec des adresses d\'exemple en renvoyant HTTP 403 – c\'est de loin la cause la plus fréquente quand la recherche ne trouve rien.',
    'check.help_calc' => 'Veuillez téléverser à nouveau tous les fichiers.',
    'check.help_tzdb' => 'La base des fuseaux horaires de PHP semble incomplète. Signalez-le à votre hébergeur.',
    'check.help_geo' => 'Le serveur atteint-il nominatim.openstreetmap.org ? Certains hébergeurs bloquent les connexions sortantes jusqu\'à ce qu\'on les ouvre.',
    'check.help_geo_empty' => 'La requête a abouti mais n\'a rien renvoyé. Réessayez plus tard.',
    'check.help_cache' => 'Facultatif. Un dossier cache/ accessible en écriture soulage OpenStreetMap.',
    'check.all_ok' => 'Tout est prêt.',
    'check.all_ok_text' => 'Aller à la page d\'accueil – et check.php peut disparaître.',
    'check.not_ok' => 'Il manque encore quelque chose.',
    'check.not_ok_text' => 'Chaque note ci-dessus indique l\'étape suivante. Après une modification, rechargez simplement cette page.',
    'check.manual' => 'À vérifier à la main',
    'check.manual_text' => 'Ouvrez lib/config.php. Une erreur 403 doit apparaître. Si le fichier s\'affiche ou se télécharge, votre hébergeur n\'exploite pas .htaccess – bloquez alors les dossiers lib, lang, data, partials et cache depuis le panneau d\'hébergement.',
    'check.to_home' => 'Aller à la page d\'accueil',
    'gen.size_hint' => 'Les périodes très longues produisent de gros fichiers : cinq ans avec tous les types d\'événements font environ 3 Mo. Certaines applications d\'agenda les chargent lentement. Deux ans en abonnement glissant est souvent le meilleur choix.',

    // Eigenbezeichnung der Sprache – erscheint in der Auswahl
    'lang.name' => 'Français',

    // Screenshots
    'screens.title' => 'Et voici le rendu dans votre agenda',
    'screens.intro' => 'Pas d\'application, pas de compte : les événements apparaissent là où vous regardez déjà.',
    'screens.week_alt' => 'Deux jours consécutifs en vue semaine, chacun avec un événement heure dorée',
    'screens.week_cap' => 'Deux jours consécutifs à Berlin : 18 h 52, puis 18 h 54. L\'heure dorée se décale d\'une à deux minutes par jour – personne ne retient cela de tête.',
    'screens.event_alt' => 'Un événement ouvert avec lieu, horaire et description',
    'screens.event_cap' => 'Chaque événement indique le début, la fin et le lieu. La langue et la description proviennent des réglages choisis à la création.',
    'screens.note' => 'Captures d\'écran d\'Apple Calendrier. Les mêmes événements apparaissent dans Google Agenda et Outlook.',

    // Anonyme Zählung
    'stats.line' => 'lighthours tient actuellement <strong>{count} agendas</strong> à jour.',
    'stats.note' => 'Ce chiffre compte les agendas abonnés, pas les personnes : qui planifie plusieurs régions apparaît plusieurs fois. Aucune adresse IP ni identifiant de navigateur n\'est conservé, seulement le fait qu\'un agenda a été récupéré.',

    // Rechtstexte
    'legal.privacy_title' => 'Confidentialité',
    'legal.imprint_title' => 'Mentions légales',
    'legal.updated' => 'Mise à jour : {date}',
    'legal.only_de_en' => 'Cette page n\'existe qu\'en allemand et en anglais. La version allemande fait foi.',
    'footer.imprint' => 'Mentions légales',

    // Suchmaschinen und soziale Netzwerke
    'meta.og_alt' => 'lighthours – heure dorée et heure bleue en abonnement calendrier',
    'meta.privacy_desc' => 'Ce que lighthours conserve et ce qu\'il ne conserve pas : aucun cookie, aucun compte, aucune régie publicitaire. Politique complète.',
    'meta.imprint_desc' => 'Mentions légales et contact de lighthours, l\'agenda libre pour l\'heure dorée et l\'heure bleue.',
    'meta.check_desc' => 'Vérification de l\'installation.',
    'os.link' => 'Code source sur GitHub',
];
