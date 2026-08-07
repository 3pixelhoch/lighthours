/* =========================================================================
   lighthours – Kalendergenerator
   Kein Framework, keine Abhängigkeiten außer MapLibre (lokal eingebunden).
   ========================================================================= */

(function () {
  'use strict';

  var $ = function (id) { return document.getElementById(id); };
  var T = window.LH.t;

  /* ------------------------------------------------------------------ */
  /* Farbmodus: System → Hell → Dunkel                                   */
  /* Läuft vor allem anderen, damit der Umschalter auch dann arbeitet,   */
  /* wenn der Generator gar nicht auf der Seite ist.                     */
  /* ------------------------------------------------------------------ */

  (function () {
    var btn = $('theme-toggle');
    if (!btn) { return; }

    var icon  = $('theme-icon');
    var order = ['auto', 'light', 'dark'];
    var meta  = document.querySelector('meta[name="theme-color"]');

    function read() {
      try {
        var v = localStorage.getItem('lh-theme');
        return (v === 'light' || v === 'dark') ? v : 'auto';
      } catch (e) { return 'auto'; }
    }

    function apply(mode) {
      var root = document.documentElement;

      if (mode === 'auto') { root.removeAttribute('data-theme'); }
      else { root.setAttribute('data-theme', mode); }

      icon.setAttribute('href', mode === 'auto' ? '#icon-auto'
                              : mode === 'light' ? '#icon-sun' : '#icon-moon');

      var label = btn.dataset['label' + mode.charAt(0).toUpperCase() + mode.slice(1)];
      btn.setAttribute('aria-label', label);
      btn.setAttribute('title', label);

      // Adressleiste mobiler Browser mitfärben
      if (meta) {
        var dark = mode === 'dark' || (mode === 'auto'
                 && window.matchMedia('(prefers-color-scheme: dark)').matches);
        meta.setAttribute('content', dark ? '#0E1014' : '#C97B2C');
      }

      try { 
        if (mode === 'auto') { localStorage.removeItem('lh-theme'); }
        else { localStorage.setItem('lh-theme', mode); }
      } catch (e) { /* Speicher gesperrt – Wahl gilt nur für diesen Besuch */ }
    }

    apply(read());

    btn.addEventListener('click', function () {
      apply(order[(order.indexOf(read()) + 1) % order.length]);
    });
  })();

  /* ------------------------------------------------------------------ */
  /* Sprachwahl: außerhalb klicken oder Escape schließt die Liste         */
  /* <details> allein bleibt sonst offen stehen, bis man erneut darauf    */
  /* tippt – auf dem Telefon besonders lästig.                           */
  /* ------------------------------------------------------------------ */

  (function () {
    var menue = $('lang-menu');
    if (!menue) { return; }

    document.addEventListener('click', function (ev) {
      if (menue.open && !menue.contains(ev.target)) { menue.open = false; }
    });

    document.addEventListener('keydown', function (ev) {
      if (ev.key === 'Escape' && menue.open) {
        menue.open = false;
        var knopf = menue.querySelector('summary');
        if (knopf) { knopf.focus(); }
      }
    });
  })();

  if (!$('q')) { return; }

  var state = {
    lat: null,
    lon: null,
    name: '',
    tz: '',
    map: null,
    marker: null,
    previewTimer: null
  };

  /* ------------------------------------------------------------------ */
  /* Ortssuche                                                           */
  /* ------------------------------------------------------------------ */

  function showMessage(text, isError) {
    var el = $('search-msg');
    el.textContent = text;
    el.className = 'msg' + (isError ? ' msg-error' : '');
    el.hidden = !text;
  }

  function search() {
    var q = $('q').value.trim();
    $('results').hidden = true;
    if (q.length < 2) { return; }

    showMessage(T.searching, false);

    fetch('api/geocode.php?q=' + encodeURIComponent(q) + '&lang=' + window.LH.lang)
      .then(function (r) {
        // Auch Fehlerantworten enthalten JSON mit der Ursache – die ist für den
        // Betreiber weit nützlicher als ein allgemeines "klappt gerade nicht".
        return r.json().then(function (data) { return { ok: r.ok, data: data }; });
      })
      .then(function (res) {
        if (!res.ok || res.data.error) {
          showMessage(res.data.error || T.error, true);
          return;
        }
        if (!res.data.results || !res.data.results.length) {
          showMessage(T.noResults, false);
          return;
        }
        showMessage('', false);
        renderResults(res.data.results);
      })
      .catch(function () { showMessage(T.error, true); });
  }

  function renderResults(items) {
    var ul = $('results');
    ul.innerHTML = '';

    items.forEach(function (item) {
      var li = document.createElement('li');
      var btn = document.createElement('button');
      btn.type = 'button';

      var main = document.createElement('span');
      main.className = 'place-main';
      main.textContent = item.short || item.name.split(',')[0];

      var sub = document.createElement('span');
      sub.className = 'place-sub';
      // Region und Land: bei mehrdeutigen Eingaben der entscheidende Unterschied
      sub.textContent = item.region || item.name.split(',').slice(1).join(',').trim();

      btn.appendChild(main);
      btn.appendChild(sub);
      btn.addEventListener('click', function () { selectPlace(item); });
      li.appendChild(btn);
      ul.appendChild(li);
    });

    ul.hidden = false;
  }

  $('search-btn').addEventListener('click', search);
  $('q').addEventListener('keydown', function (ev) {
    if (ev.key === 'Enter') { ev.preventDefault(); search(); }
  });

  /* ------------------------------------------------------------------ */
  /* Ortsauswahl                                                         */
  /* ------------------------------------------------------------------ */

  function selectPlace(item) {
    state.lat = item.lat;
    state.lon = item.lon;
    state.name = item.short || item.name.split(',')[0];

    fillTimezones(item.timezones || [], item.timezone);

    $('results').hidden = true;
    $('q').value = state.name;

    ['step-area', 'step-options', 'step-preview', 'step-output'].forEach(function (id) {
      $(id).hidden = false;
    });

    initMap();
    update();

    $('step-area').scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function fillTimezones(zones, preferred) {
    var sel = $('tz');
    sel.innerHTML = '';

    var list = zones.slice();
    if (preferred && list.indexOf(preferred) === -1) { list.unshift(preferred); }

    // Zeitzone des Browsers anbieten, falls sie im Land vorkommt
    var browserTz = '';
    try { browserTz = Intl.DateTimeFormat().resolvedOptions().timeZone || ''; } catch (e) { /* egal */ }

    if (!list.length) { list = [preferred || browserTz || 'UTC']; }

    list.forEach(function (z) {
      var opt = document.createElement('option');
      opt.value = z;
      opt.textContent = z.replace(/_/g, ' ');
      sel.appendChild(opt);
    });

    var choice = preferred;
    if (browserTz && list.indexOf(browserTz) !== -1) { choice = browserTz; }
    sel.value = choice || list[0];
    state.tz = sel.value;
    sel.disabled = list.length < 2;
  }

  /* ------------------------------------------------------------------ */
  /* Karte                                                               */
  /* ------------------------------------------------------------------ */

  function circle(lat, lon, km) {
    var points = 90;
    var coords = [];
    var latR = km / 110.574;
    var lonR = km / (111.32 * Math.cos((lat * Math.PI) / 180));

    for (var i = 0; i <= points; i++) {
      var a = (i / points) * 2 * Math.PI;
      coords.push([lon + lonR * Math.cos(a), lat + latR * Math.sin(a)]);
    }
    return { type: 'Feature', geometry: { type: 'Polygon', coordinates: [coords] } };
  }

  function initMap() {
    if (state.map) {
      state.map.easeTo({ center: [state.lon, state.lat], zoom: zoomForRadius() });
      state.marker.setLngLat([state.lon, state.lat]);
      drawCircle();
      return;
    }

    state.map = new maplibregl.Map({
      container: 'map',
      center: [state.lon, state.lat],
      zoom: zoomForRadius(),
      attributionControl: { compact: true },
      style: {
        version: 8,
        sources: {
          osm: {
            type: 'raster',
            tiles: ['https://tile.openstreetmap.org/{z}/{x}/{y}.png'],
            tileSize: 256,
            maxzoom: 19,
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
          }
        },
        layers: [{ id: 'osm', type: 'raster', source: 'osm' }]
      }
    });

    state.map.addControl(new maplibregl.NavigationControl({ showCompass: false }), 'top-right');

    state.marker = new maplibregl.Marker({ draggable: true, color: '#C97B2C' })
      .setLngLat([state.lon, state.lat])
      .addTo(state.map);

    state.marker.on('dragend', function () {
      var p = state.marker.getLngLat();
      state.lat = p.lat;
      state.lon = p.lng;
      update();
    });

    state.map.on('click', function (ev) {
      state.lat = ev.lngLat.lat;
      state.lon = ev.lngLat.lng;
      state.marker.setLngLat(ev.lngLat);
      update();
    });

    state.map.on('load', drawCircle);
  }

  function radiusKm() {
    var v = $('radius').value;
    if (v !== 'custom') { return Number(v); }
    return Math.max(1, Math.min(500, Number($('radius-custom').value) || 50));
  }

  function zoomForRadius() {
    var km = radiusKm();
    if (km <= 25) { return 9; }
    if (km <= 50) { return 8.2; }
    if (km <= 100) { return 7.4; }
    if (km <= 150) { return 6.9; }
    return 6.2;
  }

  function drawCircle() {
    if (!state.map || !state.map.isStyleLoaded()) { return; }

    var data = circle(state.lat, state.lon, radiusKm());
    var src = state.map.getSource('radius');

    if (src) {
      src.setData(data);
      return;
    }

    state.map.addSource('radius', { type: 'geojson', data: data });
    state.map.addLayer({
      id: 'radius-fill', type: 'fill', source: 'radius',
      paint: { 'fill-color': '#C97B2C', 'fill-opacity': 0.1 }
    });
    state.map.addLayer({
      id: 'radius-line', type: 'line', source: 'radius',
      paint: { 'line-color': '#C97B2C', 'line-width': 1.5, 'line-opacity': 0.8 }
    });
  }

  /* ------------------------------------------------------------------ */
  /* Abweichung, Vorschau, Links                                         */
  /* ------------------------------------------------------------------ */

  function updateDeviation() {
    var km = radiusKm();
    var kmPerDeg = 111.32 * Math.max(Math.cos((state.lat * Math.PI) / 180), 0.05);
    var minutes = Math.max(1, Math.round((km / kmPerDeg) * 4 * 1.25));
    $('deviation').innerHTML = T.deviation.replace('{minutes}', minutes);
  }

  function selectedEvents() {
    return Array.prototype.slice
      .call(document.querySelectorAll('input[name="events"]:checked'))
      .map(function (c) { return c.value; });
  }

  function buildUrl() {
    var params = new URLSearchParams();
    params.set('lat', state.lat.toFixed(5));
    params.set('lon', state.lon.toFixed(5));
    params.set('lang', $('cal-lang').value);
    params.set('tz', $('tz').value);
    if (state.name) { params.set('name', state.name); }

    var events = selectedEvents();
    if (events.length && events.length < 4) { params.set('events', events.join(',')); }

    var period = $('period').value;
    if (period === 'custom' && $('end-date').value) {
      params.set('end', $('end-date').value);
    } else if (period !== 'custom') {
      params.set('months', period);
    }

    if ($('rolling').checked) { params.set('rolling', '1'); }
    if ($('reminder').value) { params.set('reminder', $('reminder').value); }
    if ($('prep').value) { params.set('prep', $('prep').value); }

    var days = selectedWeekdays();
    if (days && days.length && days.length < 7) { params.set('days', days.join(',')); }

    return window.LH.base + '/calendar.php?' + params.toString();
  }

  function updatePreview() {
    var events = selectedEvents();
    if (!events.length) { $('preview').innerHTML = ''; return; }

    var url = 'api/times.php?lat=' + state.lat.toFixed(5) +
              '&lon=' + state.lon.toFixed(5) +
              '&tz=' + encodeURIComponent($('tz').value) +
              '&lang=' + $('cal-lang').value +
              '&days=2&events=' + events.join(',');

    fetch(url)
      .then(function (r) { return r.json(); })
      .then(function (data) {
        var ul = $('preview');
        ul.innerHTML = '';

        (data.days || []).forEach(function (day, index) {
          day.phases.forEach(function (p) {
            var li = document.createElement('li');

            var d = document.createElement('span');
            d.className = 'p-day';
            d.textContent = index === 0 ? T.today : T.tomorrow;

            var n = document.createElement('span');
            n.className = 'p-name';
            n.textContent = p.label;

            var t = document.createElement('span');
            t.className = 'p-time';
            t.textContent = p.start_local + ' – ' + p.end_local;

            li.appendChild(d);
            li.appendChild(n);
            li.appendChild(t);
            ul.appendChild(li);
          });
        });
      })
      .catch(function () { /* Vorschau ist optional */ });
  }

  function updateSizeHint() {
    var el = $('size-hint');
    if (!el) { return; }

    // Ab drei Jahren mit allen vier Terminarten wird die Datei groß genug,
    // dass manche Kalender-Apps spürbar länger laden.
    var monate = Number($('period').value) || 0;
    el.hidden = !(monate >= 36 && selectedEvents().length >= 3);
  }

  function updateRollingHint() {
    var period = $('period').value;
    var hint = $('rolling-hint');

    if (!$('rolling').checked || period === 'custom') {
      hint.textContent = '';
      return;
    }
    hint.textContent = T.rolling.replace('{months}', T.periods[period] || period);
  }

  function update() {
    if (state.lat === null) { return; }

    state.tz = $('tz').value;

    updateDeviation();
    drawCircle();
    updateRollingHint();
    updateSizeHint();

    var url = buildUrl();
    $('cal-link').value = url;
    $('download-btn').href = url;
    $('subscribe-btn').href = url.replace(/^https?:\/\//, 'webcal://');

    clearTimeout(state.previewTimer);
    state.previewTimer = setTimeout(updatePreview, 250);
  }

  /* ------------------------------------------------------------------ */
  /* Ereignisse                                                          */
  /* ------------------------------------------------------------------ */

  $('radius').addEventListener('change', function () {
    $('radius-custom').hidden = $('radius').value !== 'custom';
    if (state.map) { state.map.easeTo({ zoom: zoomForRadius() }); }
    update();
  });
  $('radius-custom').addEventListener('input', update);

  $('period').addEventListener('change', function () {
    $('end-date').hidden = $('period').value !== 'custom';
    update();
  });

  /* Gewählte Wochentage als Zahlenliste, 1 = Montag. Leere Auswahl gilt als
     "alle Tage" - ein Kalender ohne Termine sieht für den Nutzer wie ein
     Fehler aus, nicht wie eine Einstellung. */
  function selectedWeekdays() {
    var wahl = $('weekdays').value;
    if (wahl === 'all') { return null; }
    if (wahl !== 'custom') { return wahl.split(','); }

    var an = [].slice.call($('weekday-grid').querySelectorAll('input:checked'))
      .map(function (el) { return el.value; });
    return an.length ? an : null;
  }

  $('weekdays').addEventListener('change', function () {
    $('weekday-grid').hidden = this.value !== 'custom';
    update();
  });

  [].slice.call($('weekday-grid').querySelectorAll('input')).forEach(function (el) {
    el.addEventListener('change', update);
  });

  ['end-date', 'rolling', 'cal-lang', 'reminder', 'prep', 'tz'].forEach(function (id) {
    $(id).addEventListener('change', update);
  });

  document.querySelectorAll('input[name="events"]').forEach(function (c) {
    c.addEventListener('change', update);
  });

  /* ------------------------------------------------------------------ */
  /* Freiwilliger Versand des Links per E-Mail                           */
  /* ------------------------------------------------------------------ */

  if (window.LH.mail && $('mail-btn')) {
    $('mail-btn').addEventListener('click', function () {
      var btn = $('mail-btn');
      var msg = $('mail-msg');
      var adresse = $('mail-address').value.trim();

      function zeige(text, fehler) {
        msg.textContent = text;
        msg.className = 'msg' + (fehler ? ' msg-error' : ' msg-ok');
        msg.hidden = !text;
      }

      if (!adresse) { return; }

      btn.disabled = true;
      zeige('', false);

      fetch('api/send.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          email: adresse,
          url: $('cal-link').value,
          name: state.name,
          lang: $('cal-lang').value,
          website: $('mail-hp') ? $('mail-hp').value : ''
        })
      })
        .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
        .then(function (res) {
          if (res.ok && res.data.sent) {
            zeige(btn.dataset.done || '✓', false);
            $('mail-address').value = '';
          } else {
            zeige(res.data.error || btn.dataset.failed || '', true);
          }
        })
        .catch(function () { zeige(btn.dataset.failed || '', true); })
        .finally(function () { btn.disabled = false; });
    });
  }

  $('copy-btn').addEventListener('click', function () {
    var btn = $('copy-btn');
    var input = $('cal-link');

    function done() {
      btn.textContent = btn.dataset.copied;
      setTimeout(function () { btn.textContent = btn.dataset.copy; }, 1600);
    }

    if (navigator.clipboard) {
      navigator.clipboard.writeText(input.value).then(done, fallback);
    } else {
      fallback();
    }

    function fallback() {
      input.select();
      try { document.execCommand('copy'); done(); } catch (e) { /* nichts zu tun */ }
    }
  });
})();
