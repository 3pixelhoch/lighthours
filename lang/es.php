<?php
/**
 * Textos en español.
 *
 * Para añadir un idioma: copiar este archivo, traducir los valores y guardarlo
 * como <código>.php. El idioma aparece automáticamente en el selector.
 */

declare(strict_types=1);

return [
    // Calendario
    'event.golden_morning' => 'Hora dorada (mañana)',
    'event.golden_evening' => 'Hora dorada (tarde)',
    'event.blue_morning'   => 'Hora azul (mañana)',
    'event.blue_evening'   => 'Hora azul (tarde)',
    'cal.name'             => 'lighthours – {name}',
    'cal.description'      => 'Hora dorada y hora azul para {name}. Creado con lighthours.',

    // Diagramm: warum die Goldene Stunde nach Sonnenuntergang endet
    'chart.heading' => 'Por qué la hora dorada termina después de la puesta de sol',
    'chart.svg_title' => 'Altura del sol a lo largo de una tarde',
    'chart.svg_desc' => 'El sol desciende desde seis grados sobre el horizonte hasta menos seis grados. La banda inferior muestra las fases: la hora dorada va de más seis a menos cuatro grados y por tanto continúa más allá de la puesta de sol. Solo después llega la hora azul.',
    'chart.horizon' => 'Horizonte',
    'chart.sunset' => 'Puesta de sol',
    'chart.legend_golden' => 'Hora dorada, +6° a −4°',
    'chart.legend_after' => 'de ella, tras la puesta de sol',
    'chart.legend_blue' => 'Hora azul, −4° a −6°',
    'chart.text_1' => 'Las fases se definen por la altura del sol, no por si se le ve. Su luz no se acaba con la puesta: sigue iluminando la atmósfera desde abajo, y esa luz llega dispersa al suelo. Es cálida, muy suave y ya no proyecta sombras. Por eso la zona de color más intenso sigue contando como hora dorada. Solo a −4° el resplandor pierde fuerza, el propio cielo se convierte en la fuente de luz y empieza la hora azul.',
    'chart.text_2' => 'Para planificar significa: hasta la puesta, la luz llega de lado y modela; es el momento para personas y paisaje. Después queda un cielo cálido para siluetas y tomas urbanas, pero nada con lo que iluminar un rostro. Por eso cada evento del calendario indica también la puesta de sol, para saber en qué punto de ese intervalo se está.',

    // Aufteilung an der Horizontgrenze und zweite Erinnerung
    'cal.sun_above' => 'Sol sobre el horizonte: {from}–{to}',
    'cal.alarm_prep' => 'Prepárate: {event} empieza pronto',

    // Wochentagswahl und Vorbereitungszeit im Generator
    'gen.days_label' => 'Días de la semana',
    'gen.days_all' => 'Todos los días',
    'gen.days_week' => 'De lunes a viernes',
    'gen.days_weekend' => 'Fin de semana',
    'gen.days_custom' => 'Selección propia',
    'gen.days_hint' => 'Menos días significan menos entradas en el calendario. Quien solo fotografía el fin de semana no necesita la semana.',
    'gen.prep_label' => 'Tiempo de preparación',
    'gen.prep_none' => 'Ninguno',
    'gen.prep_hint' => 'Un aviso adicional y más temprano, solo en la hora dorada, para que no sean demasiadas notificaciones.',
    'gen.d1' => 'Lu',
    'gen.d2' => 'Ma',
    'gen.d3' => 'Mi',
    'gen.d4' => 'Ju',
    'gen.d5' => 'Vi',
    'gen.d6' => 'Sá',
    'gen.d7' => 'Do',

    // Rückkanal für falsche Zeiten
    'report.title' => '¿Los horarios no te cuadran?',
    'report.text' => 'Un horario equivocado es el único fallo que no puedo encontrar solo, porque no puedo comprobar todos los lugares del mundo. Si ves algo raro, dímelo.',
    'report.link' => 'Informar de una desviación',
    'footer.report' => 'Informar de un error',
    'cal.event_description' => "{event} en {name}\nInicio: {start}\nFin: {end}\n{sun}\n\nCreado con lighthours – planificación de luz para foto y vídeo.",
    'cal.sunrise' => "Amanecer: {time}",
    'cal.sunset' => "Puesta de sol: {time}",

    // Cabecera
    'meta.title'       => 'lighthours – Hora dorada y hora azul como calendario suscribible',
    'meta.description' => 'Calendario gratuito con los horarios diarios de la hora dorada y la hora azul para cualquier lugar del mundo. Suscribible en Apple Calendario, Google Calendar y Outlook. Código abierto.',
    'nav.skip'         => 'Ir al contenido',

    // Inicio
    'hero.tagline'  => 'La mejor luz.<br>Directa en tu calendario.',
    'hero.intro'    => 'lighthours calcula los horarios diarios de la hora dorada y la hora azul para cualquier lugar del mundo y los entrega como calendario: te suscribes una vez y te olvidas.',
    'hero.cta'      => 'Crear calendario',
    'hero.badge'    => 'Gratis · Sin cuenta · Código abierto',

    'why.title'     => 'Por qué importan estas horas',
    'why.golden'    => 'Hora dorada',
    'why.golden_t'  => 'Poco después del amanecer y antes del atardecer el sol está bajo. La luz se vuelve suave, cálida y direccional: favorece los tonos de piel, da profundidad al paisaje y elimina las sombras duras.',
    'why.blue'      => 'Hora azul',
    'why.blue_t'    => 'Antes y después llega el crepúsculo: el cielo brilla en azul profundo mientras la luz artificial y la residual se equilibran. Es el momento de la ciudad, la arquitectura y la atmósfera.',
    'why.short'     => 'Breve',
    'why.short_t'   => 'Juntas rara vez duran más de una hora y se desplazan cada día. Tenerlas en el calendario significa planificar de forma realista, en vez de descubrir sobre el terreno que la luz ya se fue.',

    'how.title'   => 'Cómo funciona',
    'how.step1'   => 'Introduce un lugar',
    'how.step1_t' => 'Basta una ciudad, una dirección o un código postal. Nunca hace falta buscar coordenadas.',
    'how.step2'   => 'Elige las opciones',
    'how.step2_t' => 'Qué fases de luz, qué periodo, qué recordatorio. En el mapa puedes ajustar el centro.',
    'how.step3'   => 'Suscríbete',
    'how.step3_t' => 'Un clic y los eventos están en tu calendario. Con la suscripción se actualizan solos.',

    'free.title' => '¿Por qué es gratis?',
    'free.text'  => 'El cálculo no cuesta más que un poco de cómputo, y la posición del sol no es de nadie. Sin cuentas, sin publicidad, sin rastreo y sin muro de pago, tampoco más adelante.',
    'os.title'   => '¿Por qué código abierto?',
    'os.text'    => 'El código fuente está a la vista y puede usarse libremente. Quien quiera puede alojar lighthours en su propio espacio web. Ningún servicio que cierre algún día llevándose tus calendarios.',
    'privacy.title' => '¿Y los datos?',
    'privacy.text'  => 'Sin cookies, sin rastreadores, sin visitas registradas. Lo único que se guarda es tu elección de modo de color, de forma local en el navegador. Las tipografías están en este servidor y la búsqueda de lugares pasa por el backend: tus términos llegan a OpenStreetMap sin tu dirección IP. Única excepción: las teselas del mapa las carga tu navegador directamente desde openstreetmap.org, una vez elegido un lugar. El número de calendarios activos se cuenta de forma anónima: solo se guarda un hash de los ajustes del calendario, sin dirección IP y sin marca de tiempo.',

    // Generador
    'gen.title'    => 'Crear calendario',
    'gen.subtitle' => 'Tres pasos, sin cuenta y sin correo electrónico.',

    'gen.step_place'   => 'Lugar',
    'gen.search_label' => 'Ciudad, dirección o código postal',
    'gen.search_ph'    => 'p. ej. Valencia, 46001 o Malvarrosa',
    'gen.search_btn'   => 'Buscar',
    'gen.searching'    => 'Buscando …',
    'gen.no_results'   => 'No se encontró nada. Prueba con una ciudad más grande cercana.',
    'gen.geo_error'    => 'La búsqueda de lugares no responde ahora mismo. Inténtalo de nuevo en un momento.',

    'gen.step_area'    => 'Zona',
    'gen.map_hint'     => 'Arrastra el marcador o toca el mapa para ajustar el centro.',
    'gen.radius_label' => 'Radio',
    'gen.radius_custom' => 'Radio personalizado',
    'gen.radius_info'  => 'En esta zona los horarios de luz varían como mucho unos <strong>{minutes} minutos</strong>.',
    'gen.radius_why'   => 'Por eso no necesitas un calendario por localización: uno solo cubre toda tu región.',
    'gen.timezone'     => 'Zona horaria',
    'gen.timezone_hint' => 'Los eventos aparecen en la hora local de este lugar.',

    'gen.step_options' => 'Opciones',
    'gen.events_label' => '¿Qué eventos?',
    'gen.period_label' => 'Periodo',
    'gen.period_3'     => '3 meses',
    'gen.period_6'     => '6 meses',
    'gen.period_12'    => '1 año',
    'gen.period_24'    => '2 años',
    'gen.period_36'    => '3 años',
    'gen.period_60'    => '5 años',
    'gen.period_custom' => 'Fecha final personalizada',
    'gen.rolling'      => 'Suscripción continua',
    'gen.rolling_hint' => 'El calendario avanza contigo: siempre contiene {months} a partir de hoy, sin que tengas que hacer nada.',
    'gen.lang_label'   => 'Idioma de los eventos',
    'gen.reminder'     => 'Recordatorio',
    'gen.reminder_none' => 'Ninguno',
    'gen.reminder_15'  => '15 minutos antes',
    'gen.reminder_30'  => '30 minutos antes',
    'gen.reminder_60'  => '60 minutos antes',

    'gen.preview'      => 'Vista previa',
    'gen.preview_hint' => 'Los próximos eventos en este lugar:',
    'gen.today'        => 'Hoy',
    'gen.tomorrow'     => 'Mañana',

    'gen.subscribe'    => 'Suscribirse al calendario',
    'gen.subscribe_hint' => 'Abre tu aplicación de calendario. Los eventos se mantendrán al día solos.',
    'gen.download'     => 'Descargar ICS',
    'gen.download_hint' => 'Un archivo único para importar, sin actualizaciones.',
    'gen.link_label'   => 'O copia el enlace de suscripción',
    'gen.copy'         => 'Copiar',
    'gen.copied'       => 'Copiado',

    'gen.help_title'   => 'Configurar la suscripción',
    'gen.help_apple'   => '<strong>Apple Calendario:</strong> toca «Suscribirse al calendario» y el resto es automático.',
    'gen.help_google'  => '<strong>Google Calendar:</strong> copia el enlace y añádelo en «Añadir calendario → Desde URL».',
    'gen.help_outlook' => '<strong>Outlook:</strong> copia el enlace y luego «Agregar calendario → Suscribirse desde la web».',

    // Pie de página
    'footer.tagline' => 'Planificación de luz para foto y vídeo.',
    'footer.source'  => 'Código fuente',
    'footer.api'     => 'API',
    'footer.privacy' => 'Privacidad',
    'footer.data'    => 'Datos de lugares de OpenStreetMap',
    'footer.free'    => 'De uso libre bajo licencia MIT.',

    // Modo de color
    'theme.auto'  => 'Modo: sistema',
    'theme.light' => 'Modo: claro',
    'theme.dark'  => 'Modo: oscuro',

    // Configuración
    'setup.title' => 'La configuración aún no está completa',
    'setup.text'  => 'En <code>lib/config.php</code>, <code>LH_USER_AGENT</code> todavía contiene la dirección de ejemplo. OpenStreetMap rechaza esas peticiones, así que la búsqueda de lugares no puede funcionar. Pon una dirección de contacto real y funcionará al instante.',
    'setup.check' => 'Abrir la comprobación completa',

    // Navegación, lugares, correo, apoyo
    'nav.language'  => 'Idioma',
    'nav.footer'    => 'Más páginas',
    'nav.generator' => 'Calendario',

    'mail.title'       => 'Enviar el enlace por correo',
    'mail.hint'        => 'Opcional. La dirección se usa solo para este mensaje y no se guarda en ningún sitio.',
    'mail.placeholder' => 'tu@ejemplo.es',
    'mail.send'        => 'Enviar',
    'mail.sent'        => 'Enviado: mira tu bandeja de entrada.',
    'mail.failed'      => 'El envío no funcionó. Inténtalo más tarde o copia el enlace.',
    'mail.invalid'     => 'Esa dirección de correo no parece correcta.',
    'mail.too_many'    => 'Demasiados mensajes en poco tiempo. Inténtalo más tarde.',
    'mail.your_place'  => 'tu lugar',
    'mail.subject'     => 'Tu calendario lighthours para {name}',
    'mail.body_intro'  => "Aquí tienes tu calendario personal para {name}.\n\nUna vez suscrito se actualiza solo: no tendrás que volver a pensar en ello.",
    'mail.link_label'  => 'Si el botón no funciona, aquí tienes el enlace para copiar:',
    'mail.footer'      => 'Este mensaje se envió una sola vez a petición tuya. Tu dirección no se guardó y no recibirás nada más.',
    'mail.body_text'   => "Aquí tienes tu calendario lighthours personal para {name}.\n\nSuscribirse al calendario:\n{webcal}\n\nO añade este enlace en tu aplicación de calendario:\n{url}\n\nCómo hacerlo:\n- Apple Calendario: abre el enlace, el resto es automático.\n- Google Calendar: Configuración, luego Añadir calendario, luego Desde URL.\n- Outlook: Agregar calendario, luego Suscribirse desde la web.\n\nEste mensaje se envió una sola vez a petición tuya. Tu dirección no se guardó.",

    'support.title'  => '¿Te gusta lighthours?',
    'support.text'   => 'El proyecto es gratuito y seguirá siéndolo. Sin publicidad, sin cuentas, sin muro de pago. Si te resulta útil puedes invitar a un café, aunque no hace ninguna falta.',
    'support.button' => 'Invítame a un café',
    'support.note'   => 'Lleva a Buy Me a Coffee. No hay ningún script suyo incrustado: solo tu clic sale de esta página.',

    // Self-check
    'check.title' => 'Comprobación',
    'check.intro' => 'Esta página comprueba que todo esté listo en este servidor. Cuando cada línea muestre una marca, puedes borrar check.php.',
    'check.php_version' => 'Versión de PHP',
    'check.ext' => 'Extensión {name}',
    'check.ext_ok' => 'presente',
    'check.ext_missing' => 'falta',
    'check.outgoing' => 'Conexiones salientes posibles',
    'check.via_curl' => 'mediante cURL',
    'check.via_fopen' => 'mediante allow_url_fopen',
    'check.via_none' => 'ni cURL ni allow_url_fopen',
    'check.contact' => 'Dirección de contacto configurada',
    'check.contact_missing' => 'todavía la dirección de ejemplo',
    'check.calc' => 'Cálculo astronómico',
    'check.calc_ok' => 'Hora dorada el 21 de junio en Hamburgo: {time}',
    'check.tzdb' => 'Base de datos de zonas horarias',
    'check.geo' => 'Búsqueda de lugares (prueba con el código postal 20095)',
    'check.geo_ok' => '{count} resultados, primero: {first}',
    'check.geo_none' => 'sin resultados',
    'check.geo_skipped' => 'omitida: introduce primero la dirección de contacto',
    'check.cache' => 'Caché con permiso de escritura',
    'check.yes' => 'sí',
    'check.cache_no' => 'no: funciona igual, solo más lento',
    'check.help_php' => 'lighthours necesita PHP 8.1 o superior. Cámbialo en el panel de hosting en «Ajustes de PHP».',
    'check.help_ext' => 'La extensión {name} debe estar activada. Pregunta a tu proveedor.',
    'check.help_outgoing' => 'Sin una de las dos opciones la búsqueda de lugares no puede funcionar.',
    'check.help_contact' => 'En lib/config.php pon una dirección real en LH_USER_AGENT, por ejemplo: lighthours/1.0 (+https://tu-subdominio.es; tu@tu-dominio.es). OpenStreetMap rechaza las peticiones con direcciones de ejemplo con HTTP 403: es con diferencia la causa más frecuente cuando la búsqueda no encuentra nada.',
    'check.help_calc' => 'Vuelve a subir todos los archivos.',
    'check.help_tzdb' => 'La base de datos de zonas horarias de PHP parece incompleta. Comunícaselo a tu proveedor.',
    'check.help_geo' => '¿Llega el servidor a nominatim.openstreetmap.org? Algunos proveedores bloquean las conexiones salientes hasta que se solicitan.',
    'check.help_geo_empty' => 'La petición funcionó pero no devolvió nada. Inténtalo más tarde.',
    'check.help_cache' => 'Opcional. Una carpeta cache/ con permiso de escritura descarga a OpenStreetMap.',
    'check.all_ok' => 'Todo listo.',
    'check.all_ok_text' => 'Ir a la página de inicio, y check.php ya puede desaparecer.',
    'check.not_ok' => 'Todavía falta algo.',
    'check.not_ok_text' => 'Cada aviso de arriba indica el siguiente paso. Tras un cambio, recarga esta página.',
    'check.manual' => 'Aún por comprobar a mano',
    'check.manual_text' => 'Abre lib/config.php. Debe aparecer un error 403. Si en su lugar se muestra o descarga el archivo, tu proveedor no evalúa .htaccess: bloquea entonces las carpetas lib, lang, data, partials y cache desde el panel de hosting.',
    'check.to_home' => 'Ir a la página de inicio',
    'gen.size_hint' => 'Los periodos muy largos generan archivos grandes: cinco años con todos los tipos de evento son unos 3 MB. Algunas aplicaciones de calendario los cargan con lentitud. Dos años como suscripción continua suele ser la mejor opción.',

    // Eigenbezeichnung der Sprache – erscheint in der Auswahl
    'lang.name' => 'Español',

    // Screenshots
    'screens.title' => 'Y así se ve en tu calendario',
    'screens.intro' => 'Sin aplicación y sin cuenta: los eventos aparecen donde ya miras.',
    'screens.week_alt' => 'Dos días consecutivos en la vista semanal, cada uno con un evento de hora dorada',
    'screens.week_cap' => 'Dos días seguidos en Berlín: 18:52 y luego 18:54. La hora dorada se desplaza uno o dos minutos cada día: nadie retiene eso de memoria.',
    'screens.event_alt' => 'Un evento con lugar, horarios y puesta de sol',
    'screens.event_cap' => 'Cada evento indica inicio, fin, lugar y la puesta de sol como referencia. El idioma y la descripción provienen de los ajustes que elegiste al crearlo.',
    // Beschriftungen der nachgezeichneten Terminkarte
    'screens.card_date' => 'Martes, 4 de agosto',
    'screens.card_above' => 'Sol sobre el horizonte',
    'screens.card_alarms' => 'Avisos 2 horas y 30 minutos antes',
    'screens.card_source' => 'Creado con lighthours',
    'screens.note' => 'Ambas figuras son representaciones dibujadas. Los mismos datos aparecen en Apple Calendar, Google Calendar y Outlook; hay capturas de una app real en el repositorio.',

    // Anonyme Zählung
    'stats.line' => 'lighthours mantiene al día <strong>{count} calendarios</strong>.',
    'stats.note' => 'Se cuentan calendarios suscritos, no personas: quien planifica varias regiones aparece varias veces. No se guarda ninguna dirección IP ni identificador de navegador, solo el hecho de que se solicitó un calendario.',

    // Rechtstexte
    'legal.privacy_title' => 'Privacidad',
    'legal.imprint_title' => 'Aviso legal',
    'legal.updated' => 'Actualizado el {date}',
    'legal.only_de_en' => 'Esta página solo existe en alemán e inglés. La versión alemana es la que prevalece.',
    'footer.imprint' => 'Aviso legal',

    // Suchmaschinen und soziale Netzwerke
    'meta.og_alt' => 'lighthours – hora dorada y hora azul como calendario',
    'meta.privacy_desc' => 'Qué guarda lighthours y qué no: sin cookies, sin cuentas, sin redes publicitarias. Declaración completa.',
    'meta.imprint_desc' => 'Aviso legal y contacto de lighthours, el calendario de código abierto para la hora dorada y la hora azul.',
    'meta.check_desc' => 'Comprobación de la instalación.',
    'os.link' => 'Código fuente en GitHub',
];
