<?php
/**
 * Testi italiani.
 *
 * Per aggiungere una lingua: copiare questo file, tradurre i valori e salvarlo
 * come <codice>.php. La lingua compare automaticamente nel selettore.
 */

declare(strict_types=1);

return [
    // Calendario
    'event.golden_morning' => 'Ora d\'oro (mattina)',
    'event.golden_evening' => 'Ora d\'oro (sera)',
    'event.blue_morning'   => 'Ora blu (mattina)',
    'event.blue_evening'   => 'Ora blu (sera)',
    'cal.name'             => 'lighthours – {name}',
    'cal.description'      => 'Ora d\'oro e ora blu per {name}. Creato con lighthours.',
    'cal.event_description' => "{event} a {name}\nInizio: {start}\nFine: {end}\n\nCreato con lighthours – pianificazione della luce per foto e video.",

    // Intestazione
    'meta.title'       => 'lighthours – Ora d\'oro e ora blu come calendario da sottoscrivere',
    'meta.description' => 'Calendario gratuito con gli orari quotidiani dell\'ora d\'oro e dell\'ora blu per qualsiasi luogo del mondo. Sottoscrivibile in Apple Calendario, Google Calendar e Outlook. Open source.',
    'nav.skip'         => 'Vai al contenuto',

    // Home
    'hero.tagline'  => 'La luce migliore.<br>Direttamente nel tuo calendario.',
    'hero.intro'    => 'lighthours calcola gli orari quotidiani dell\'ora d\'oro e dell\'ora blu per qualsiasi luogo del mondo e li fornisce come calendario: lo sottoscrivi una volta e non ci pensi più.',
    'hero.cta'      => 'Crea il calendario',
    'hero.badge'    => 'Gratuito · Senza account · Open source',

    'why.title'     => 'Perché queste ore contano',
    'why.golden'    => 'Ora d\'oro',
    'why.golden_t'  => 'Poco dopo l\'alba e prima del tramonto il sole è basso. La luce diventa morbida, calda e direzionale: gli incarnati risultano gradevoli, i paesaggi acquistano profondità, le ombre dure spariscono.',
    'why.blue'      => 'Ora blu',
    'why.blue_t'    => 'Prima e dopo arriva il crepuscolo: il cielo brilla di un blu profondo mentre luce artificiale e luce residua si equilibrano. È il momento di città, architettura e atmosfera.',
    'why.short'     => 'Breve',
    'why.short_t'   => 'Insieme durano raramente più di un\'ora e si spostano ogni giorno. Averle in calendario significa pianificare in modo realistico, invece di scoprire sul posto che la luce è già andata.',

    'how.title'   => 'Come funziona',
    'how.step1'   => 'Inserisci un luogo',
    'how.step1_t' => 'Bastano una città, un indirizzo o un CAP. Le coordinate non servono mai.',
    'how.step2'   => 'Scegli le opzioni',
    'how.step2_t' => 'Quali fasi di luce, quale periodo, quale promemoria. Sulla mappa puoi regolare il centro.',
    'how.step3'   => 'Sottoscrivi',
    'how.step3_t' => 'Un clic e gli eventi sono nel tuo calendario. Con la sottoscrizione si aggiornano da soli.',

    'free.title' => 'Perché è gratuito?',
    'free.text'  => 'Il calcolo non costa nulla se non un po\' di elaborazione, e la posizione del sole non è di nessuno. Niente account, niente pubblicità, niente tracciamento e nessun paywall – nemmeno in futuro.',
    'os.title'   => 'Perché open source?',
    'os.text'    => 'Il codice sorgente è consultabile e liberamente utilizzabile. Chi vuole può ospitare lighthours sul proprio spazio web. Nessun servizio che un giorno chiude portandosi via i tuoi calendari.',
    'privacy.title' => 'E i dati?',
    'privacy.text'  => 'Nessun cookie, nessun tracciante, nessuna visita registrata. L\'unica cosa conservata è la scelta della modalità di colore, salvata localmente nel browser. I caratteri sono ospitati su questo server e la ricerca dei luoghi passa dal backend: i tuoi termini di ricerca raggiungono OpenStreetMap senza il tuo indirizzo IP. Unica eccezione: le mappe vengono caricate dal tuo browser direttamente da openstreetmap.org, una volta scelto un luogo. Il numero di calendari attivi viene contato in forma anonima: si salva soltanto un hash delle impostazioni del calendario, senza indirizzo IP e senza orario.',

    // Generatore
    'gen.title'    => 'Crea il calendario',
    'gen.subtitle' => 'Tre passaggi – senza account, senza indirizzo email.',

    'gen.step_place'   => 'Luogo',
    'gen.search_label' => 'Città, indirizzo o CAP',
    'gen.search_ph'    => 'ad es. Milano, 20121 o Navigli',
    'gen.search_btn'   => 'Cerca',
    'gen.searching'    => 'Ricerca in corso …',
    'gen.no_results'   => 'Nessun risultato. Prova con una città più grande nelle vicinanze.',
    'gen.geo_error'    => 'La ricerca dei luoghi non risponde al momento. Riprova tra poco.',

    'gen.step_area'    => 'Area',
    'gen.map_hint'     => 'Trascina il segnaposto o tocca la mappa per spostare il centro.',
    'gen.radius_label' => 'Raggio',
    'gen.radius_custom' => 'Raggio personalizzato',
    'gen.radius_info'  => 'In questa area gli orari della luce differiscono al massimo di circa <strong>{minutes} minuti</strong>.',
    'gen.radius_why'   => 'Per questo non serve un calendario per ogni location: uno solo copre l\'intera regione.',
    'gen.timezone'     => 'Fuso orario',
    'gen.timezone_hint' => 'Gli eventi compaiono nell\'ora locale di questo luogo.',

    'gen.step_options' => 'Opzioni',
    'gen.events_label' => 'Quali eventi?',
    'gen.period_label' => 'Periodo',
    'gen.period_3'     => '3 mesi',
    'gen.period_6'     => '6 mesi',
    'gen.period_12'    => '1 anno',
    'gen.period_24'    => '2 anni',
    'gen.period_36'    => '3 anni',
    'gen.period_60'    => '5 anni',
    'gen.period_custom' => 'Data di fine personalizzata',
    'gen.rolling'      => 'Sottoscrizione continua',
    'gen.rolling_hint' => 'Il calendario si sposta con te: contiene sempre {months} a partire da oggi, senza che tu debba fare nulla.',
    'gen.lang_label'   => 'Lingua degli eventi',
    'gen.reminder'     => 'Promemoria',
    'gen.reminder_none' => 'Nessuno',
    'gen.reminder_15'  => '15 minuti prima',
    'gen.reminder_30'  => '30 minuti prima',
    'gen.reminder_60'  => '60 minuti prima',

    'gen.preview'      => 'Anteprima',
    'gen.preview_hint' => 'I prossimi eventi in questo luogo:',
    'gen.today'        => 'Oggi',
    'gen.tomorrow'     => 'Domani',

    'gen.subscribe'    => 'Sottoscrivi il calendario',
    'gen.subscribe_hint' => 'Apre la tua app calendario. Gli eventi resteranno aggiornati da soli.',
    'gen.download'     => 'Scarica ICS',
    'gen.download_hint' => 'Un file singolo da importare, senza aggiornamenti.',
    'gen.link_label'   => 'Oppure copia il link di sottoscrizione',
    'gen.copy'         => 'Copia',
    'gen.copied'       => 'Copiato',

    'gen.help_title'   => 'Come sottoscrivere',
    'gen.help_apple'   => '<strong>Apple Calendario:</strong> tocca «Sottoscrivi il calendario» – il resto è automatico.',
    'gen.help_google'  => '<strong>Google Calendar:</strong> copia il link, poi aggiungilo in «Aggiungi calendario → Da URL».',
    'gen.help_outlook' => '<strong>Outlook:</strong> copia il link, poi «Aggiungi calendario → Sottoscrivi dal Web».',

    // Piè di pagina
    'footer.tagline' => 'Pianificazione della luce per foto e video.',
    'footer.source'  => 'Codice sorgente',
    'footer.api'     => 'API',
    'footer.privacy' => 'Privacy',
    'footer.data'    => 'Dati dei luoghi da OpenStreetMap',
    'footer.free'    => 'Liberamente utilizzabile con licenza MIT.',

    // Modalità colore
    'theme.auto'  => 'Modalità: sistema',
    'theme.light' => 'Modalità: chiara',
    'theme.dark'  => 'Modalità: scura',

    // Configurazione
    'setup.title' => 'Configurazione non ancora completata',
    'setup.text'  => 'In <code>lib/config.php</code> il valore <code>LH_USER_AGENT</code> contiene ancora l\'indirizzo segnaposto. OpenStreetMap rifiuta richieste di questo tipo, quindi la ricerca dei luoghi non può funzionare. Inserisci un indirizzo di contatto reale e tutto funzionerà subito.',
    'setup.check' => 'Apri la verifica completa',

    // Navigazione, luoghi, email, supporto
    'nav.language'  => 'Lingua',
    'nav.footer'    => 'Altre pagine',
    'nav.generator' => 'Calendario',

    'mail.title'       => 'Invia il link per email',
    'mail.hint'        => 'Facoltativo. L\'indirizzo serve solo per questo messaggio e non viene conservato.',
    'mail.placeholder' => 'tuo@indirizzo.it',
    'mail.send'        => 'Invia',
    'mail.sent'        => 'Inviato – controlla la posta.',
    'mail.failed'      => 'Invio non riuscito. Riprova più tardi oppure copia il link.',
    'mail.invalid'     => 'Questo indirizzo email non sembra corretto.',
    'mail.too_many'    => 'Troppi messaggi in poco tempo. Riprova più tardi.',
    'mail.your_place'  => 'il tuo luogo',
    'mail.subject'     => 'Il tuo calendario lighthours per {name}',
    'mail.body_intro'  => "Ecco il tuo calendario personale per {name}.\n\nUna volta sottoscritto si aggiorna da solo: non dovrai più pensarci.",
    'mail.link_label'  => 'Se il pulsante non funziona, ecco il link da copiare:',
    'mail.footer'      => 'Questo messaggio è stato inviato una sola volta su tua richiesta. Il tuo indirizzo non è stato conservato e non riceverai altre email.',
    'mail.body_text'   => "Ecco il tuo calendario lighthours personale per {name}.\n\nSottoscrivi il calendario:\n{webcal}\n\nOppure inserisci questo link nella tua app calendario:\n{url}\n\nCome fare:\n- Apple Calendario: apri il link, il resto è automatico.\n- Google Calendar: Impostazioni, poi Aggiungi calendario, poi Da URL.\n- Outlook: Aggiungi calendario, poi Sottoscrivi dal Web.\n\nQuesto messaggio è stato inviato una sola volta su tua richiesta. Il tuo indirizzo non è stato conservato.",

    'support.title'  => 'Ti piace lighthours?',
    'support.text'   => 'Il progetto è gratuito e resterà tale. Niente pubblicità, niente account, nessun paywall. Se ti è utile puoi offrire un caffè, ma non è necessario.',
    'support.button' => 'Offrimi un caffè',
    'support.note'   => 'Porta a Buy Me a Coffee. Nessuno script esterno è incorporato: solo il tuo clic lascia questa pagina.',

    // Self-check
    'check.title' => 'Verifica',
    'check.intro' => 'Questa pagina controlla che tutto sia pronto su questo server. Quando ogni riga mostra un segno di spunta puoi eliminare check.php.',
    'check.php_version' => 'Versione PHP',
    'check.ext' => 'Estensione {name}',
    'check.ext_ok' => 'presente',
    'check.ext_missing' => 'mancante',
    'check.outgoing' => 'Connessioni in uscita possibili',
    'check.via_curl' => 'tramite cURL',
    'check.via_fopen' => 'tramite allow_url_fopen',
    'check.via_none' => 'né cURL né allow_url_fopen',
    'check.contact' => 'Indirizzo di contatto configurato',
    'check.contact_missing' => 'ancora l\'indirizzo segnaposto',
    'check.calc' => 'Calcolo astronomico',
    'check.calc_ok' => 'Ora d\'oro il 21 giugno ad Amburgo: {time}',
    'check.tzdb' => 'Database dei fusi orari',
    'check.geo' => 'Ricerca luoghi (prova con il CAP 20095)',
    'check.geo_ok' => '{count} risultati, primo: {first}',
    'check.geo_none' => 'nessun risultato',
    'check.geo_skipped' => 'saltata – inserisci prima l\'indirizzo di contatto',
    'check.cache' => 'Cache scrivibile',
    'check.yes' => 'sì',
    'check.cache_no' => 'no – funziona lo stesso, solo più lento',
    'check.help_php' => 'lighthours richiede PHP 8.1 o superiore. Modificalo nel pannello di hosting sotto «Impostazioni PHP».',
    'check.help_ext' => 'L\'estensione {name} deve essere attiva. Chiedi al tuo host.',
    'check.help_outgoing' => 'Senza una delle due possibilità la ricerca dei luoghi non può funzionare.',
    'check.help_contact' => 'In lib/config.php imposta LH_USER_AGENT con un indirizzo reale, ad esempio: lighthours/1.0 (+https://tuo-sottodominio.it; tu@tuo-dominio.it). OpenStreetMap rifiuta le richieste con segnaposto restituendo HTTP 403: è di gran lunga la causa più frequente quando la ricerca non trova nulla.',
    'check.help_calc' => 'Ricarica tutti i file.',
    'check.help_tzdb' => 'Il database dei fusi orari di PHP sembra incompleto. Segnalalo al tuo host.',
    'check.help_geo' => 'Il server raggiunge nominatim.openstreetmap.org? Alcuni host bloccano le connessioni in uscita finché non le si richiede.',
    'check.help_geo_empty' => 'La richiesta è andata a buon fine ma non ha restituito nulla. Riprova più tardi.',
    'check.help_cache' => 'Facoltativo. Una cartella cache/ scrivibile alleggerisce OpenStreetMap.',
    'check.all_ok' => 'Tutto pronto.',
    'check.all_ok_text' => 'Vai alla pagina iniziale – e check.php può sparire.',
    'check.not_ok' => 'Manca ancora qualcosa.',
    'check.not_ok_text' => 'Ogni nota qui sopra indica il passo successivo. Dopo una modifica ricarica questa pagina.',
    'check.manual' => 'Da verificare a mano',
    'check.manual_text' => 'Apri lib/config.php. Deve comparire un errore 404. Se invece il file viene mostrato o scaricato, il tuo host non valuta .htaccess: blocca allora le cartelle lib, lang, data, partials e cache dal pannello di hosting.',
    'check.to_home' => 'Vai alla pagina iniziale',
    'gen.size_hint' => 'Periodi molto lunghi generano file grandi: cinque anni con tutti i tipi di evento sono circa 3 MB. Alcune app calendario li caricano lentamente. Due anni con sottoscrizione continua sono di solito la scelta migliore.',

    // Eigenbezeichnung der Sprache – erscheint in der Auswahl
    'lang.name' => 'Italiano',

    // Screenshots
    'screens.title' => 'Ed ecco come appare nel calendario',
    'screens.intro' => 'Nessuna app, nessun account: gli eventi compaiono dove già guardi.',
    'screens.week_alt' => 'Due giorni consecutivi nella vista settimanale, ciascuno con un evento dell\'ora d\'oro',
    'screens.week_cap' => 'Due giorni consecutivi a Berlino: 18:52, poi 18:54. L\'ora d\'oro si sposta di uno o due minuti al giorno: nessuno se lo ricorda a memoria.',
    'screens.event_alt' => 'Un evento aperto con luogo, orario e descrizione',
    'screens.event_cap' => 'Ogni evento indica inizio, fine e luogo. Lingua e descrizione derivano dalle impostazioni scelte alla creazione.',
    'screens.note' => 'Schermate da Apple Calendario. Gli stessi eventi compaiono in Google Calendar e Outlook.',

    // Anonyme Zählung
    'stats.line' => 'lighthours tiene aggiornati <strong>{count} calendari</strong>.',
    'stats.note' => 'Si contano i calendari sottoscritti, non le persone: chi pianifica più regioni compare più volte. Non viene salvato alcun indirizzo IP né identificatore del browser, solo il fatto che un calendario è stato richiesto.',

    // Rechtstexte
    'legal.privacy_title' => 'Privacy',
    'legal.imprint_title' => 'Note legali',
    'legal.updated' => 'Aggiornato il {date}',
    'legal.only_de_en' => 'Questa pagina esiste solo in tedesco e inglese. Fa fede la versione tedesca.',
    'footer.imprint' => 'Note legali',

    // Suchmaschinen und soziale Netzwerke
    'meta.og_alt' => 'lighthours – ora d\'oro e ora blu come calendario',
    'meta.privacy_desc' => 'Cosa lighthours salva e cosa no: niente cookie, niente account, niente reti pubblicitarie. Informativa completa.',
    'meta.imprint_desc' => 'Dati dell\'editore e contatti di lighthours, il calendario open source per l\'ora d\'oro e l\'ora blu.',
    'meta.check_desc' => 'Verifica dell\'installazione.',
    'os.link' => 'Codice sorgente su GitHub',
];
