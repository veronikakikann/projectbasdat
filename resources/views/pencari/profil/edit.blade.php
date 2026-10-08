@extends('pencari.layout')

@section('title', 'Edit Profil')

@section('content')

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<style>
    .form-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        max-width: 900px;
        padding: 2.5rem;
    }

    .page-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        color: var(--color-ink);
    }

    .page-desc {
        color: var(--color-ink-soft);
        font-size: 0.9rem;
        margin-bottom: 2rem;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--color-ink);
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--color-border);
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        color: var(--color-ink);
        transition: 0.2s;
        background: white;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(85, 180, 234, 0.1);
    }

    .form-control[readonly] {
        background: #F8FAFC;
        color: var(--color-ink-soft);
        cursor: not-allowed;
    }

    .form-control.is-invalid {
        border-color: #EF4444;
        background-color: #FEF2F2;
    }

    .invalid-feedback {
        color: #DC2626;
        font-size: 0.8rem;
        font-weight: 500;
        margin-top: 0.4rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .form-help {
        font-size: 0.8rem;
        color: var(--color-ink-soft);
        margin-top: 0.35rem;
        line-height: 1.4;
    }

    .section-divider {
        border: 0;
        border-top: 1px solid var(--color-border);
        margin: 2rem 0;
    }

    .section-heading {
        font-family: 'Manrope', sans-serif;
        font-size: 1.1rem;
        font-weight: 800;
        margin-bottom: 0.4rem;
        color: var(--color-ink);
    }

    .section-desc {
        font-size: 0.85rem;
        color: var(--color-ink-soft);
        margin-bottom: 1.25rem;
        line-height: 1.5;
    }

    .location-map {
        width: 100%;
        height: 350px;
        border-radius: 8px;
        border: 1px solid var(--color-border);
        overflow: hidden;
        z-index: 1;
    }

    .map-helper {
        font-size: 0.8rem;
        color: var(--color-ink-soft);
        margin-top: 0.5rem;
        line-height: 1.5;
    }

    .btn-submit {
        background-color: var(--color-primary);
        color: white;
        padding: 0.8rem 2rem;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-submit:hover {
        background-color: var(--color-primary-dark);
    }

    .btn-cancel {
        background: white;
        border: 1px solid var(--color-border);
        color: var(--color-ink);
    }

    .btn-cancel:hover {
        background: #F8FAFC;
    }

    .button-row {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    @media (max-width: 700px) {

        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .form-card {
            padding: 1.5rem;
        }
    }
</style>

<div class="form-card">

```
<h2 class="page-title">
    Edit Informasi Profil
</h2>

<p class="page-desc">
    Perbarui data dirimu agar informasi profil tetap sesuai.
    Lokasi tempat tinggal dipilih melalui peta dan digunakan
    untuk pencocokan lowongan berdasarkan jarak.
</p>

<form
    action="{{ route('pencari.profil.update') }}"
    method="POST"
>

    @csrf
    @method('PUT')


    <!-- DATA UTAMA -->

    <div class="form-row">

        <div class="form-group">

            <label
                for="nama"
                class="form-label"
            >
                Nama Lengkap
            </label>

            <input
                type="text"
                id="nama"
                name="nama"
                class="form-control @error('nama') is-invalid @enderror"
                value="{{ old('nama', $pencari->nama) }}"
                required
            >

            @error('nama')
                <div class="invalid-feedback">
                    ⚠️ {{ $message }}
                </div>
            @enderror

        </div>


        <div class="form-group">

            <label
                for="no_telpon"
                class="form-label"
            >
                Nomor Telepon
            </label>

            <input
                type="text"
                id="no_telpon"
                name="no_telpon"
                class="form-control @error('no_telpon') is-invalid @enderror"
                value="{{ old('no_telpon', $pencari->no_telpon) }}"
                required
            >

            @error('no_telpon')
                <div class="invalid-feedback">
                    ⚠️ {{ $message }}
                </div>
            @enderror

        </div>

    </div>


    <!-- EMAIL -->

    <div class="form-group">

        <label
            for="email"
            class="form-label"
        >
            Alamat Email
        </label>

        <input
            type="email"
            id="email"
            name="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $pencari->email) }}"
            required
        >

        @error('email')
            <div class="invalid-feedback">
                ⚠️ {{ $message }}
            </div>
        @enderror

    </div>


    <!-- NIK -->

    <div class="form-group">

        <label
            for="nik"
            class="form-label"
        >
            NIK KTP
        </label>

        <input
            type="text"
            id="nik"
            class="form-control"
            value="{{ $pencari->nik }}"
            readonly
        >

        <div class="form-help">
            NIK tidak dapat diubah melalui halaman profil.
        </div>

    </div>


    <!-- ALAMAT -->

    <div class="form-group">

        <label
            for="alamat"
            class="form-label"
        >
            Alamat Lengkap
        </label>

        <textarea
            id="alamat"
            name="alamat"
            class="form-control @error('alamat') is-invalid @enderror"
            style="min-height: 100px;"
            required
        >{{ old('alamat', $pencari->alamat) }}</textarea>

        @error('alamat')
            <div class="invalid-feedback">
                ⚠️ {{ $message }}
            </div>
        @enderror

    </div>


    <!-- LOKASI -->

    <hr class="section-divider">

    <h3 class="section-heading">
        Lokasi Tempat Tinggal
    </h3>

    <p class="section-desc">
        Pilih lokasi tempat tinggalmu pada peta.
        Geser pin atau klik langsung pada peta untuk menentukan
        titik lokasi. Latitude dan longitude akan tersimpan
        otomatis dan tidak perlu diisi manual.
    </p>

    <div class="form-group">

        <div
            id="profile-map"
            class="location-map"
        ></div>

        <div class="map-helper">
            Geser pin atau klik pada peta untuk memperbarui lokasi.
        </div>

        <input
            type="hidden"
            name="latitude"
            id="latitude"
            value="{{ old('latitude', $pencari->latitude ?? '-7.2674') }}"
        >

        <input
            type="hidden"
            name="longitude"
            id="longitude"
            value="{{ old('longitude', $pencari->longitude ?? '112.7725') }}"
        >

        @error('latitude')
            <div class="invalid-feedback">
                ⚠️ {{ $message }}
            </div>
        @enderror

        @error('longitude')
            <div class="invalid-feedback">
                ⚠️ {{ $message }}
            </div>
        @enderror

    </div>


    <!-- PASSWORD -->

    <hr class="section-divider">

    <h3 class="section-heading">
        Ubah Password
    </h3>

    <p class="section-desc">
        Biarkan kedua kolom di bawah ini kosong jika tidak ingin
        mengubah password akun.
    </p>

    <div class="form-row">

        <div class="form-group">

            <label
                for="password"
                class="form-label"
            >
                Password Baru
            </label>

            <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Min. 8 karakter (huruf & angka)"
            >

            @error('password')
                <div class="invalid-feedback">
                    ⚠️ {{ $message }}
                </div>
            @enderror

        </div>


        <div class="form-group">

            <label
                for="password_confirmation"
                class="form-label"
            >
                Konfirmasi Password Baru
            </label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control"
                placeholder="Ketik ulang password baru"
            >

        </div>

    </div>


    <!-- BUTTON -->

    <div class="button-row">

        <button
            type="submit"
            class="btn-submit"
        >
            Simpan Perubahan
        </button>

        <a
            href="{{ route('pencari.profil') }}"
            class="btn-submit btn-cancel"
        >
            Batal
        </a>

    </div>

</form>
```

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const latitudeInput =
            document.getElementById('latitude');

        const longitudeInput =
            document.getElementById('longitude');

        const defaultLat = -7.2674;
        const defaultLng = 112.7725;

        const initialLat =
            parseFloat(latitudeInput.value) || defaultLat;

        const initialLng =
            parseFloat(longitudeInput.value) || defaultLng;


        const map =
            L.map('profile-map')
                .setView(
                    [initialLat, initialLng],
                    14
                );


        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }
        ).addTo(map);


        const marker =
            L.marker(
                [initialLat, initialLng],
                {
                    draggable: true
                }
            ).addTo(map);


        function updateCoordinates() {

            const position =
                marker.getLatLng();

            latitudeInput.value =
                position.lat;

            longitudeInput.value =
                position.lng;
        }


        marker.on(
            'dragend',
            updateCoordinates
        );


        map.on(
            'click',
            function (event) {

                marker.setLatLng(
                    event.latlng
                );

                updateCoordinates();
            }
        );


        /*
         * Pastikan koordinat tetap mengikuti posisi pin
         * ketika halaman pertama kali dimuat.
         */
        updateCoordinates();

    });
</script>

@endsection