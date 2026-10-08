@extends('pemberi.layout')

@section('title', 'Buat Lowongan Baru')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    .form-card {
        background: white; border-radius: 12px; padding: 2.5rem;
        border: 1px solid var(--color-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02); max-width: 900px;
    }
    .form-group { margin-bottom: 1.5rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; }
    .form-label { display: block; font-size: 0.9rem; font-weight: 600; color: var(--color-ink); margin-bottom: 0.5rem; }
    
    .form-control {
        width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border);
        border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 0.95rem;
        color: var(--color-ink); transition: border-color 0.2s;
    }
    .form-control:focus { outline: none; border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(85, 180, 234, 0.1); }
    
    /* --- STYLING KHUSUS UNTUK ERROR --- */
    .form-control.is-invalid {
        border-color: #EF4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        background-color: #FEF2F2;
    }
    .invalid-feedback {
        color: #DC2626; font-size: 0.8rem; font-weight: 500; margin-top: 0.4rem; display: flex; align-items: center; gap: 4px;
    }
    /* ---------------------------------- */

    textarea.form-control { resize: vertical; min-height: 100px; }
    #map { height: 350px; width: 100%; border-radius: 8px; border: 1px solid var(--color-border); z-index: 1; }
    .map-helper { font-size: 0.8rem; color: var(--color-ink-soft); margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem; }
    
    .btn-submit {
        background-color: var(--color-primary); color: white; padding: 0.8rem 2rem;
        border: none; border-radius: 8px; font-weight: 700; font-size: 1rem;
        cursor: pointer; transition: 0.2s; margin-top: 1rem; width: 100%;
    }
    .btn-submit:hover { background-color: var(--color-primary-dark); }
</style>

<div class="form-card">
    
    <!-- Kotak Alert General jika ada error -->
    @if ($errors->any())
        <div style="background: #FEF2F2; border: 1px solid #EF4444; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; display: flex; align-items: flex-start; gap: 1rem;">
            <div style="font-size: 1.5rem;">⚠️</div>
            <div>
                <strong style="color: #991B1B; font-size: 0.95rem; display: block; margin-bottom: 0.2rem;">Gagal Menerbitkan Lowongan</strong>
                <span style="color: #B91C1C; font-size: 0.85rem;">Ada beberapa isian yang belum tepat. Silakan periksa kolom berwarna merah di bawah ini.</span>
            </div>
        </div>
    @endif

    <form action="{{ route('pemberi.pekerjaan.store') }}" method="POST">
        @csrf
        
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Pekerjaan</label>
                <input type="text" name="nama_pekerjaan" class="form-control @error('nama_pekerjaan') is-invalid @enderror" value="{{ old('nama_pekerjaan') }}" placeholder="Cth: Bantu Pindahan Rumah">
                @error('nama_pekerjaan')
                    <div class="invalid-feedback">Form ini harus diisi dengan benar.</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Kategori Keahlian</label>
                <select name="id_keahlian" class="form-control @error('id_keahlian') is-invalid @enderror">
                    <option value="">-- Pilih Keahlian --</option>
                    
                    <!-- Loop data kategori keahlian dari database -->
                    @foreach($keahlian as $k)
                        <option value="{{ $k->id_keahlian }}" {{ old('id_keahlian') == $k->id_keahlian ? 'selected' : '' }}>
                            {{ $k->nama_keahlian }}
                        </option>
                    @endforeach
                    
                </select>
                @error('id_keahlian')
                    <div class="invalid-feedback">Pilih salah satu kategori keahlian yang tersedia.</div>
                @enderror
            </div>
        </div>

        <div class="form-row-3">
            <div class="form-group">
                <label class="form-label">Upah / Gaji (Rp)</label>
                <input type="number" name="upah" class="form-control @error('upah') is-invalid @enderror" value="{{ old('upah') }}" placeholder="Cth: 150000" min="0">
                @error('upah')
                    <div class="invalid-feedback">Masukkan nominal angka yang valid.</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Pengerjaan</label>
                <input type="date" name="tanggal_pengerjaan" class="form-control @error('tanggal_pengerjaan') is-invalid @enderror" value="{{ old('tanggal_pengerjaan') }}">
                @error('tanggal_pengerjaan')
                    <div class="invalid-feedback">Tanggal tidak boleh berlalu (harus hari ini atau ke depan).</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Pekerja</label>
                <input type="number" name="jumlah_pekerja" class="form-control @error('jumlah_pekerja') is-invalid @enderror" value="{{ old('jumlah_pekerja', 1) }}" min="1">
                @error('jumlah_pekerja')
                    <div class="invalid-feedback">Minimal butuh 1 pekerja.</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi Pekerjaan</label>
            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Jelaskan detail tugas yang harus dikerjakan...">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <div class="invalid-feedback">Deskripsi wajib diisi (maksimal 2000 karakter).</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Persyaratan Pekerja</label>
            <textarea name="persyaratan" class="form-control @error('persyaratan') is-invalid @enderror" placeholder="Cth: Laki-laki, kuat angkat berat, membawa kendaraan sendiri..." style="min-height: 80px;">{{ old('persyaratan') }}</textarea>
            @error('persyaratan')
                <div class="invalid-feedback">Isian persyaratan tidak valid.</div>
            @enderror
        </div>

        <hr style="border: 0; border-top: 1px solid var(--color-border); margin: 2rem 0;">

        <div class="form-group">
            <label class="form-label">Alamat Lengkap (Lokasi)</label>
            <textarea name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" placeholder="Cth: Jl. Mulyorejo Utara No. 10..." style="min-height: 80px;">{{ old('lokasi') }}</textarea>
            @error('lokasi')
                <div class="invalid-feedback">Alamat lengkap wajib diisi.</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Titik Lokasi Peta</label>
            <div id="map" class="@error('latitude') is-invalid @enderror @error('longitude') is-invalid @enderror"></div>
            <div class="map-helper">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                Geser pin biru ke lokasi yang tepat agar pencari kerja terdekat bisa menemukan lowongan ini.
            </div>
            @if($errors->has('latitude') || $errors->has('longitude'))
                <div class="invalid-feedback" style="margin-top: 0.5rem;">Tolong geser pin lokasi di peta untuk menentukan koordinat.</div>
            @endif
            
            <!-- Menggunakan old() agar pin peta tidak kembali ke awal jika terjadi error -->
            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', '-7.2674') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', '112.7725') }}">
        </div>

        <button type="submit" class="btn-submit">Terbitkan Lowongan</button>
    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Mengambil koordinat dari input hidden (bisa dari default atau dari old value jika ada error)
        const initialLat = parseFloat(document.getElementById('latitude').value) || -7.2674;
        const initialLng = parseFloat(document.getElementById('longitude').value) || 112.7725;
        
        const map = L.map('map').setView([initialLat, initialLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const marker = L.marker([initialLat, initialLng], {
            draggable: true
        }).addTo(map);

        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
        });
    });
</script>
@endsection