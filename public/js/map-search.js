/**
 * map-search.js
 * Cari alamat -> peta (Leaflet) langsung terbang ke titiknya, pin ikut pindah,
 * dan input hidden #latitude / #longitude terisi otomatis.
 *
 * Geocoding memakai Nominatim (OpenStreetMap). Aturan pemakaiannya:
 *  - maksimal 1 permintaan per detik, jadi tidak ada "cari sambil mengetik";
 *    pencarian hanya jalan saat tombol ditekan / kolom alamat ditinggalkan.
 *  - butuh koneksi internet (sama seperti tile peta).
 *
 * Pemakaian di halaman (di dalam script peta, setelah `map` dan `marker` dibuat):
 *   MapSearch.attach({ map: map, marker: marker, addressField: '[name="alamat"]' });
 */
(function (global) {
    'use strict';

    var ENDPOINT = 'https://nominatim.openstreetmap.org/search';
    // kotak pencarian utama: Surabaya dan sekitarnya (kiri, atas, kanan, bawah)
    var VIEWBOX = '112.45,-7.05,113.05,-7.55';
    var MIN_INTERVAL_MS = 1100;

    function sleep(ms) {
        return new Promise(function (resolve) { setTimeout(resolve, ms); });
    }

    // "Jl. Mulyorejo Utara No. 10 RT 02 RW 03" -> "Jl. Mulyorejo Utara"
    function simplify(text) {
        return text
            .replace(/\b(no\.?|nomor)\s*\d+[a-z]?(\s*[-\/]\s*\d+[a-z]?)?/gi, ' ')
            .replace(/\brt\.?\s*\d+(\s*\/\s*\d+)?/gi, ' ')
            .replace(/\brw\.?\s*\d+/gi, ' ')
            .replace(/\bblok\s*[a-z0-9\-]+/gi, ' ')
            .replace(/\s{2,}/g, ' ')
            .replace(/\s+,/g, ',')
            .replace(/^[\s,]+|[\s,]+$/g, '');
    }

    function Geocoder(fetchImpl, sleepImpl) {
        this.fetch = fetchImpl;
        this.sleep = sleepImpl;
        this.lastRequestAt = 0;
    }

    Geocoder.prototype.request = function (q, bounded) {
        var self = this;
        var wait = Math.max(0, self.lastRequestAt + MIN_INTERVAL_MS - Date.now());
        return self.sleep(wait).then(function () {
            self.lastRequestAt = Date.now();
            var params = new URLSearchParams({
                q: q,
                format: 'jsonv2',
                limit: '1',
                countrycodes: 'id',
                'accept-language': 'id'
            });
            if (bounded) {
                params.set('viewbox', VIEWBOX);
                params.set('bounded', '1');
            }
            return self.fetch(ENDPOINT + '?' + params.toString(), {
                headers: { 'Accept': 'application/json' }
            });
        }).then(function (res) {
            if (!res.ok) { throw new Error('HTTP ' + res.status); }
            return res.json();
        }).then(function (list) {
            if (!list || !list.length) { return null; }
            var item = list[0];
            var lat = parseFloat(item.lat), lon = parseFloat(item.lon);
            if (isNaN(lat) || isNaN(lon)) { return null; }
            return { lat: lat, lon: lon, name: item.display_name || '' };
        });
    };

    // urutan: teks asli di sekitar Surabaya -> teks disederhanakan -> seluruh Indonesia
    Geocoder.prototype.find = function (text) {
        var self = this;
        var original = text.trim();
        var simple = simplify(original);
        var steps = [{ q: original, bounded: true }];
        if (simple && simple !== original) { steps.push({ q: simple, bounded: true }); }
        steps.push({ q: simple || original, bounded: false });

        return steps.reduce(function (chain, step) {
            return chain.then(function (found) {
                return found ? found : self.request(step.q, step.bounded);
            });
        }, Promise.resolve(null));
    };

    var cssInjected = false;
    function injectCss() {
        if (cssInjected) { return; }
        cssInjected = true;
        var style = document.createElement('style');
        style.textContent =
            '.map-search{margin-top:.5rem;display:flex;flex-wrap:wrap;align-items:center;gap:.6rem;}' +
            '.map-search button{padding:.45rem 1rem;border:1.5px solid #55B4EA;border-radius:8px;' +
            'background:#fff;color:#171717;font:inherit;font-size:.9rem;font-weight:600;cursor:pointer;}' +
            '.map-search button:hover:not(:disabled){background:#55B4EA;color:#fff;}' +
            '.map-search button:disabled{opacity:.6;cursor:wait;}' +
            '.map-search__status{font-size:.85rem;color:#5B6472;}' +
            '.map-search__status.is-error{color:#B42318;}' +
            '.map-search__status.is-ok{color:#067647;}';
        document.head.appendChild(style);
    }

    function attach(options) {
        var map = options.map;
        var marker = options.marker;
        var field = typeof options.addressField === 'string'
            ? document.querySelector(options.addressField)
            : options.addressField;
        if (!map || !marker || !field) { return null; }

        var latInput = document.getElementById(options.latId || 'latitude');
        var lngInput = document.getElementById(options.lngId || 'longitude');
        var geocoder = options.geocoder || new Geocoder(
            function (url, opts) { return global.fetch(url, opts); }, sleep);

        injectCss();

        var box = document.createElement('div');
        box.className = 'map-search';
        var button = document.createElement('button');
        button.type = 'button';
        button.textContent = 'Cari alamat di peta';
        var status = document.createElement('span');
        status.className = 'map-search__status';
        status.setAttribute('aria-live', 'polite');
        box.appendChild(button);
        box.appendChild(status);
        field.insertAdjacentElement('afterend', box);

        var busy = false;
        var userMovedPin = false;
        var lastQuery = field.value.trim();

        function setStatus(text, kind) {
            status.textContent = text;
            status.className = 'map-search__status' + (kind ? ' is-' + kind : '');
        }

        function visible() {
            var c = map.getContainer();
            return !!(c && c.offsetParent !== null) || options.ignoreVisibility === true;
        }

        function moveTo(result) {
            var latlng = [result.lat, result.lon];
            var reduce = global.matchMedia &&
                global.matchMedia('(prefers-reduced-motion: reduce)').matches;
            marker.setLatLng(latlng);
            map.flyTo(latlng, 17, { animate: !reduce, duration: 1.2 });
            if (latInput) { latInput.value = result.lat; }
            if (lngInput) { lngInput.value = result.lon; }
        }

        function search(isAuto) {
            var text = field.value.trim();
            if (busy) { return Promise.resolve(); }
            if (text.length < 4) {
                if (!isAuto) { setStatus('Tulis alamatnya dulu, minimal nama jalan.', 'error'); }
                return Promise.resolve();
            }
            if (!visible()) { return Promise.resolve(); }

            busy = true;
            button.disabled = true;
            setStatus('Mencari alamat…');
            lastQuery = text;

            return geocoder.find(text).then(function (result) {
                if (!result) {
                    setStatus('Alamat tidak ditemukan. Coba tulis lebih singkat (nama jalan + kecamatan) ' +
                        'atau geser pin secara manual.', 'error');
                    return;
                }
                moveTo(result);
                userMovedPin = false;
                var shortName = result.name.length > 90 ? result.name.slice(0, 90) + '…' : result.name;
                setStatus('Ditemukan: ' + shortName + '. Geser pin kalau kurang pas.', 'ok');
            }).catch(function () {
                setStatus('Tidak bisa terhubung ke layanan pencarian alamat (butuh internet). ' +
                    'Geser pin secara manual.', 'error');
            }).then(function () {
                busy = false;
                button.disabled = false;
            });
        }

        button.addEventListener('click', function () { search(false); });

        // untuk kolom <input>: Enter = cari (di <textarea> Enter tetap untuk baris baru)
        if (field.tagName === 'INPUT') {
            field.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); search(false); }
            });
        }

        // otomatis cari saat kolom alamat ditinggalkan, hanya jika teksnya berubah
        // dan pengguna belum menggeser pin sendiri (supaya posisi manual tidak ditimpa)
        field.addEventListener('blur', function () {
            if (field.value.trim() !== lastQuery && !userMovedPin) { search(true); }
        });

        marker.on('dragend', function () { userMovedPin = true; });
        map.on('click', function () { userMovedPin = true; });

        return { search: search, box: box };
    }

    global.MapSearch = { attach: attach, _Geocoder: Geocoder, _simplify: simplify };
    if (typeof module !== 'undefined' && module.exports) { module.exports = global.MapSearch; }
})(typeof window !== 'undefined' ? window : globalThis);
