<?php
/**
 * Textos em português (variante brasileira).
 *
 * Para acrescentar um idioma: copie este arquivo, traduza os valores e salve
 * como <código>.php. O idioma aparece automaticamente no seletor.
 */

declare(strict_types=1);

return [
    // Calendário
    'event.golden_morning' => 'Hora dourada (manhã)',
    'event.golden_evening' => 'Hora dourada (fim de tarde)',
    'event.blue_morning'   => 'Hora azul (manhã)',
    'event.blue_evening'   => 'Hora azul (noite)',
    'cal.name'             => 'lighthours – {name}',
    'cal.description'      => 'Hora dourada e hora azul para {name}. Criado com o lighthours.',

    // Diagramm: warum die Goldene Stunde nach Sonnenuntergang endet
    'chart.heading' => 'Por que a hora dourada só termina depois do pôr do sol',
    'chart.svg_title' => 'Altura do sol ao longo de uma tarde',
    'chart.svg_desc' => 'O sol desce de seis graus acima do horizonte até menos seis graus. A faixa abaixo mostra as fases: a hora dourada vai de mais seis a menos quatro graus e continua, portanto, depois do pôr do sol. Só então vem a hora azul.',
    'chart.horizon' => 'Horizonte',
    'chart.sunset' => 'Pôr do sol',
    'chart.legend_golden' => 'Hora dourada, +6° a −4°',
    'chart.legend_after' => 'sendo parte após o pôr do sol',
    'chart.legend_blue' => 'Hora azul, −4° a −6°',
    'chart.text_1' => 'As fases são definidas pela altura do sol, não pelo fato de ele estar visível. A luz não acaba com o pôr do sol: ele continua iluminando a atmosfera por baixo, e essa luz chega dispersa ao solo. É quente, muito suave e já não projeta sombras. Por isso a área de cor mais forte ainda conta como hora dourada. Só a −4° o brilho residual perde força, o próprio céu vira a fonte de luz e começa a hora azul.',
    'chart.text_2' => 'Para o planejamento isso significa: até o pôr do sol a luz vem de lado e modela – é a hora para pessoas e paisagem. Depois resta um céu quente para silhuetas e fotos urbanas, mas nada para iluminar um rosto. Por isso cada evento do calendário informa também o pôr do sol, para você saber em que ponto desse intervalo está.',

    // Aufteilung an der Horizontgrenze und zweite Erinnerung
    'cal.sun_above' => 'Sol acima do horizonte: {from}–{to}',
    'cal.alarm_prep' => 'Preparar: {event} começa em breve',

    // Wochentagswahl und Vorbereitungszeit im Generator
    'gen.days_label' => 'Dias da semana',
    'gen.days_all' => 'Todos os dias',
    'gen.days_week' => 'De segunda a sexta',
    'gen.days_weekend' => 'Fim de semana',
    'gen.days_custom' => 'Escolha própria',
    'gen.days_hint' => 'Menos dias significam menos entradas no calendário. Quem só fotografa no fim de semana não precisa da semana.',
    'gen.prep_label' => 'Tempo de preparação',
    'gen.prep_none' => 'Nenhum',
    'gen.prep_hint' => 'Um lembrete adicional e mais cedo, apenas na hora dourada, para não virar notificação demais.',
    'gen.d1' => 'Seg',
    'gen.d2' => 'Ter',
    'gen.d3' => 'Qua',
    'gen.d4' => 'Qui',
    'gen.d5' => 'Sex',
    'gen.d6' => 'Sáb',
    'gen.d7' => 'Dom',

    // Rückkanal für falsche Zeiten
    'report.title' => 'Os horários não batem para você?',
    'report.text' => 'Horário errado é a única falha que não consigo encontrar sozinho, porque não dá para conferir cada lugar do mundo. Se algo parecer estranho, me avise.',
    'report.link' => 'Relatar uma divergência',
    'footer.report' => 'Relatar um erro',
    'cal.event_description' => "{event} em {name}\nInício: {start}\nFim: {end}\n{sun}\n\nCriado com o lighthours – planejamento de luz para foto e vídeo.",
    'cal.sunrise' => "Nascer do sol: {time}",
    'cal.sunset' => "Pôr do sol: {time}",

    // Cabeçalho
    'meta.title'       => 'lighthours – Hora dourada e hora azul como calendário assinável',
    'meta.description' => 'Calendário gratuito com os horários diários da hora dourada e da hora azul para qualquer lugar do mundo. Assinável no Apple Calendário, Google Agenda e Outlook. Código aberto.',
    'nav.skip'         => 'Ir para o conteúdo',

    // Início
    'hero.tagline'  => 'A melhor luz.<br>Direto no seu calendário.',
    'hero.intro'    => 'O lighthours calcula os horários diários da hora dourada e da hora azul para qualquer lugar do mundo e entrega tudo como calendário: você assina uma vez e não precisa mais pensar nisso.',
    'hero.cta'      => 'Criar calendário',
    'hero.badge'    => 'Gratuito · Sem cadastro · Código aberto',

    'why.title'     => 'Por que essas horas importam',
    'why.golden'    => 'Hora dourada',
    'why.golden_t'  => 'Logo depois do nascer e antes do pôr do sol, o sol fica baixo. A luz vira suave, quente e direcional: os tons de pele ficam favorecidos, a paisagem ganha profundidade e as sombras duras desaparecem.',
    'why.blue'      => 'Hora azul',
    'why.blue_t'    => 'Antes e depois vem o crepúsculo: o céu brilha em azul profundo enquanto a luz artificial e a luz remanescente se equilibram. É a hora da cidade, da arquitetura e do clima.',
    'why.short'     => 'Curta',
    'why.short_t'   => 'Juntas raramente passam de uma hora, e mudam todos os dias. Ter isso no calendário significa planejar de forma realista, em vez de descobrir no local que a luz já foi embora.',

    'how.title'   => 'Como funciona',
    'how.step1'   => 'Informe um lugar',
    'how.step1_t' => 'Basta uma cidade, um endereço ou um CEP. Você nunca precisa procurar coordenadas.',
    'how.step2'   => 'Escolha as opções',
    'how.step2_t' => 'Quais fases de luz, qual período, qual lembrete. No mapa dá para ajustar o centro.',
    'how.step3'   => 'Assine',
    'how.step3_t' => 'Um clique e os eventos estão no seu calendário. Com a assinatura, eles se atualizam sozinhos.',

    'free.title' => 'Por que é gratuito?',
    'free.text'  => 'O cálculo não custa nada além de um pouco de processamento, e a posição do sol não pertence a ninguém. Não há cadastro, publicidade, rastreamento nem cobrança – nem agora nem depois.',
    'os.title'   => 'Por que código aberto?',
    'os.text'    => 'O código-fonte está à vista e pode ser usado livremente. Quem quiser hospeda o lighthours no próprio espaço web. Nenhum serviço que um dia fecha e leva junto os seus calendários.',
    'privacy.title' => 'E os dados?',
    'privacy.text'  => 'Sem cookies, sem rastreadores, sem visitas registradas. A única coisa guardada é a sua escolha de modo de cor, salva localmente no navegador. As fontes ficam neste servidor e a busca de lugares passa pelo backend: os seus termos chegam ao OpenStreetMap sem o seu endereço IP. Única exceção: os blocos do mapa são carregados pelo seu navegador direto do openstreetmap.org, depois que você escolhe um lugar. Quantos calendários estão ativos é contado de forma anônima: guarda-se apenas um hash das configurações do calendário, sem endereço IP e sem horário.',

    // Gerador
    'gen.title'    => 'Criar calendário',
    'gen.subtitle' => 'Três passos – sem cadastro, sem e-mail.',

    'gen.step_place'   => 'Lugar',
    'gen.search_label' => 'Cidade, endereço ou CEP',
    'gen.search_ph'    => 'ex.: Curitiba, 80010-010 ou Ipanema',
    'gen.search_btn'   => 'Buscar',
    'gen.searching'    => 'Buscando …',
    'gen.no_results'   => 'Nada encontrado. Tente uma cidade maior por perto.',
    'gen.geo_error'    => 'A busca de lugares não está respondendo agora. Tente de novo daqui a pouco.',

    'gen.step_area'    => 'Área',
    'gen.map_hint'     => 'Arraste o marcador ou toque no mapa para ajustar o centro.',
    'gen.radius_label' => 'Raio',
    'gen.radius_custom' => 'Raio personalizado',
    'gen.radius_info'  => 'Nessa área os horários de luz variam no máximo cerca de <strong>{minutes} minutos</strong>.',
    'gen.radius_why'   => 'Por isso você não precisa de um calendário por locação: um só cobre a sua região inteira.',
    'gen.timezone'     => 'Fuso horário',
    'gen.timezone_hint' => 'Os eventos aparecem no horário local desse lugar.',

    'gen.step_options' => 'Opções',
    'gen.events_label' => 'Quais eventos?',
    'gen.period_label' => 'Período',
    'gen.period_3'     => '3 meses',
    'gen.period_6'     => '6 meses',
    'gen.period_12'    => '1 ano',
    'gen.period_24'    => '2 anos',
    'gen.period_36'    => '3 anos',
    'gen.period_60'    => '5 anos',
    'gen.period_custom' => 'Data final personalizada',
    'gen.rolling'      => 'Assinatura contínua',
    'gen.rolling_hint' => 'O calendário anda junto com você: sempre contém {months} a partir de hoje, sem que você precise fazer nada.',
    'gen.lang_label'   => 'Idioma dos eventos',
    'gen.reminder'     => 'Lembrete',
    'gen.reminder_none' => 'Nenhum',
    'gen.reminder_15'  => '15 minutos antes',
    'gen.reminder_30'  => '30 minutos antes',
    'gen.reminder_60'  => '60 minutos antes',

    'gen.preview'      => 'Prévia',
    'gen.preview_hint' => 'Os próximos eventos neste lugar:',
    'gen.today'        => 'Hoje',
    'gen.tomorrow'     => 'Amanhã',

    'gen.subscribe'    => 'Assinar calendário',
    'gen.add_google' => 'Google Agenda',
    'gen.add_outlook' => 'Outlook',
    'gen.add_google_hint' => 'Abre o Google Agenda com uma confirmação. O Google busca novos eventos cerca de uma vez por dia.',
    'gen.subscribe_hint' => 'Abre o seu aplicativo de calendário. Os eventos ficam atualizados sozinhos.',
    'gen.download'     => 'Baixar ICS',
    'gen.download_hint' => 'Um arquivo único para importar, sem atualizações.',
    'gen.link_label'   => 'Ou copie o link de assinatura',
    'gen.copy'         => 'Copiar',
    'gen.copied'       => 'Copiado',

    'gen.help_title'   => 'Como assinar',
    'gen.help_apple'   => '<strong>Apple Calendário:</strong> toque em «Assinar calendário» – o resto é automático.',
    'gen.help_google'  => '<strong>Google Agenda:</strong> copie o link e adicione em «Adicionar agenda → De URL».',
    'gen.help_outlook' => '<strong>Outlook:</strong> copie o link e depois «Adicionar calendário → Assinar da Web».',
    'gen.size_hint'    => 'Períodos muito longos geram arquivos grandes: cinco anos com todos os tipos de evento dão cerca de 3 MB. Alguns aplicativos de calendário carregam isso devagar. Dois anos como assinatura contínua costuma ser a melhor escolha.',

    // Rodapé
    'footer.tagline' => 'Planejamento de luz para foto e vídeo.',
    'footer.source'  => 'Código-fonte',
    'footer.api'     => 'API',
    'footer.privacy' => 'Privacidade',
    'footer.data'    => 'Dados de lugares do OpenStreetMap',
    'footer.free'    => 'Livre para usar sob a licença MIT.',
    'nav.language'   => 'Idioma',
    'nav.footer'     => 'Mais páginas',
    'nav.generator'  => 'Calendário',

    // Modo de cor
    'theme.auto'  => 'Modo: sistema',
    'theme.light' => 'Modo: claro',
    'theme.dark'  => 'Modo: escuro',

    // Configuração
    'setup.title' => 'A configuração ainda não foi concluída',
    'setup.text'  => 'Em <code>lib/config.php</code>, <code>LH_USER_AGENT</code> ainda contém o endereço de exemplo. O OpenStreetMap recusa pedidos assim, então a busca de lugares não funciona. Coloque um endereço de contato real e tudo passa a funcionar na hora.',
    'setup.check' => 'Abrir a verificação completa',

    // E-mail
    'mail.title'       => 'Enviar o link por e-mail',
    'mail.hint'        => 'Opcional. O endereço serve só para esta mensagem e não é guardado em lugar nenhum.',
    'mail.placeholder' => 'voce@exemplo.com.br',
    'mail.send'        => 'Enviar',
    'mail.sent'        => 'Enviado – confira a sua caixa de entrada.',
    'mail.failed'      => 'O envio não funcionou. Tente mais tarde ou copie o link.',
    'mail.invalid'     => 'Esse endereço de e-mail não parece correto.',
    'mail.too_many'    => 'Mensagens demais em pouco tempo. Tente mais tarde.',
    'mail.your_place'  => 'o seu lugar',
    'mail.subject'     => 'O seu calendário lighthours para {name}',
    'mail.body_intro'  => "Aqui está o seu calendário pessoal para {name}.\n\nDepois de assinado, ele se atualiza sozinho: você não precisa mais pensar nisso.",
    'mail.link_label'  => 'Se o botão não funcionar, aqui está o link para copiar:',
    'mail.footer'      => 'Esta mensagem foi enviada uma única vez a seu pedido. O seu endereço não foi guardado e você não receberá mais nada.',
    'mail.body_text'   => "Aqui está o seu calendário lighthours pessoal para {name}.\n\nAssinar o calendário:\n{webcal}\n\nOu acrescente este link no seu aplicativo de calendário:\n{url}\n\nComo fazer:\n- Apple Calendário: abra o link, o resto é automático.\n- Google Agenda: Configurações, depois Adicionar agenda, depois De URL.\n- Outlook: Adicionar calendário, depois Assinar da Web.\n\nEsta mensagem foi enviada uma única vez a seu pedido. O seu endereço não foi guardado.",

    // Apoio
    'support.title'  => 'Gostou do lighthours?',
    'support.text'   => 'O projeto é gratuito e vai continuar assim. Sem publicidade, sem cadastro, sem cobrança. Se ele te ajuda, você pode pagar um café – mas não precisa.',
    'support.button' => 'Pague um café',
    'support.note'   => 'Leva ao Buy Me a Coffee. Nenhum script de lá está embutido: só o seu clique sai desta página.',

    // Verificação
    'check.title'   => 'Verificação',
    'check.intro'   => 'Esta página confere se está tudo pronto neste servidor. Quando todas as linhas mostrarem um visto, você pode apagar o check.php.',
    'check.php_version' => 'Versão do PHP',
    'check.ext'     => 'Extensão {name}',
    'check.ext_ok'  => 'presente',
    'check.ext_missing' => 'faltando',
    'check.outgoing' => 'Conexões de saída possíveis',
    'check.via_curl' => 'via cURL',
    'check.via_fopen' => 'via allow_url_fopen',
    'check.via_none' => 'nem cURL nem allow_url_fopen',
    'check.contact' => 'Endereço de contato configurado',
    'check.contact_missing' => 'ainda o endereço de exemplo',
    'check.calc'    => 'Cálculo astronômico',
    'check.calc_ok' => 'Hora dourada em 21 de junho em Hamburgo: {time}',
    'check.tzdb'    => 'Banco de fusos horários',
    'check.geo'     => 'Busca de lugares (teste com o CEP 20095)',
    'check.geo_ok'  => '{count} resultados, primeiro: {first}',
    'check.geo_none' => 'nenhum resultado',
    'check.geo_skipped' => 'pulada – informe primeiro o endereço de contato',
    'check.cache'   => 'Cache com permissão de escrita',
    'check.yes'     => 'sim',
    'check.cache_no' => 'não – funciona mesmo assim, só mais devagar',
    'check.help_php' => 'O lighthours precisa do PHP 8.1 ou mais novo. Altere no painel de hospedagem em «Configurações do PHP».',
    'check.help_ext' => 'A extensão {name} precisa estar ativa. Pergunte à sua hospedagem.',
    'check.help_outgoing' => 'Sem uma das duas opções, a busca de lugares não funciona.',
    'check.help_contact' => 'Em lib/config.php, coloque um endereço real em LH_USER_AGENT, por exemplo: lighthours/1.0 (+https://seu-subdominio.com.br; voce@seu-dominio.com.br). O OpenStreetMap recusa pedidos com endereços de exemplo com HTTP 403 – de longe a causa mais comum quando a busca não acha nada.',
    'check.help_calc' => 'Envie todos os arquivos de novo.',
    'check.help_tzdb' => 'O banco de fusos horários do PHP parece incompleto. Avise a sua hospedagem.',
    'check.help_geo' => 'O servidor alcança o nominatim.openstreetmap.org? Algumas hospedagens bloqueiam conexões de saída até que você peça a liberação.',
    'check.help_geo_empty' => 'O pedido funcionou, mas não trouxe nada. Tente mais tarde.',
    'check.help_cache' => 'Opcional. Uma pasta cache/ com permissão de escrita alivia o OpenStreetMap.',
    'check.all_ok'  => 'Tudo pronto.',
    'check.all_ok_text' => 'Ir para a página inicial – e o check.php já pode sumir.',
    'check.not_ok'  => 'Ainda falta alguma coisa.',
    'check.not_ok_text' => 'Cada aviso acima indica o próximo passo. Depois de mudar algo, é só recarregar esta página.',
    'check.manual'  => 'Ainda para conferir na mão',
    'check.manual_text' => 'Abra lib/config.php. Precisa aparecer um erro 403. Se em vez disso o arquivo for mostrado ou baixado, a sua hospedagem não avalia o .htaccess – então bloqueie as pastas lib, lang, partials e cache pelo painel de hospedagem.',
    'check.to_home' => 'Ir para a página inicial',

    // Capturas de tela
    'screens.title'     => 'E é assim que fica no calendário',
    'screens.intro'     => 'Sem aplicativo e sem cadastro: os eventos aparecem onde você já olha.',
    'screens.week_alt'  => 'Dois dias consecutivos na visão semanal, cada um com um evento de hora dourada',
    'screens.week_cap'  => 'Dois dias seguidos em Berlim: 18:52 e depois 18:54. A hora dourada anda um ou dois minutos por dia – ninguém guarda isso de cabeça.',
    'screens.event_alt' => 'Um evento com local, horários e pôr do sol',
    'screens.event_cap' => 'Cada evento informa início, fim, local e o pôr do sol como referência. Idioma e descrição vêm das configurações escolhidas na criação.',
    // Beschriftungen der nachgezeichneten Terminkarte
    'screens.card_date' => 'Terça-feira, 4 de agosto',
    'screens.card_above' => 'Sol acima do horizonte',
    'screens.card_alarms' => 'Lembretes 2 horas e 30 minutos antes',
    'screens.card_source' => 'Criado com o lighthours',
    'screens.note'      => 'As duas figuras são representações desenhadas. As mesmas informações aparecem no Apple Calendário, Google Agenda e Outlook; capturas de um app real estão no repositório.',

    // Contagem anônima
    'stats.line' => 'O lighthours mantém <strong>{count} calendários</strong> em dia.',
    'stats.note' => 'A conta é de calendários assinados, não de pessoas: quem planeja várias regiões aparece mais de uma vez. Não se guarda endereço IP nem identificador de navegador, apenas o fato de que um calendário foi buscado.',

    // Nome do idioma
    'lang.name' => 'Português',

    // Rechtstexte
    'legal.privacy_title' => 'Privacidade',
    'legal.imprint_title' => 'Aviso legal',
    'legal.updated' => 'Atualizado em {date}',
    'legal.only_de_en' => 'Esta página existe apenas em alemão e inglês. A versão alemã é a que vale.',
    'footer.imprint' => 'Aviso legal',

    // Suchmaschinen und soziale Netzwerke
    'meta.og_alt' => 'lighthours – hora dourada e hora azul como calendário',
    'meta.privacy_desc' => 'O que o lighthours guarda e o que não guarda: sem cookies, sem contas, sem redes de publicidade. Aviso completo.',
    'meta.imprint_desc' => 'Identificação do responsável e contato do lighthours, o calendário de código aberto para hora dourada e hora azul.',
    'meta.check_desc' => 'Verificação da instalação.',
    'os.link' => 'Código-fonte no GitHub',
];
