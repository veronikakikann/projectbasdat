<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pekerjaan</title>
</head>
<body>
    <h1>Tambah Pekerjaan</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pekerjaan.store') }}" method="POST">
        @csrf

        <label>Pemberi Kerja:</label><br>
        <select name="id_pemberi">
            <option value="">-- Pilih --</option>
            @foreach($pemberiKerja as $pk)
                <option value="{{ $pk->id_pemberi }}" {{ old('id_pemberi') == $pk->id_pemberi ? 'selected' : '' }}>{{ $pk->nama }}</option>
            @endforeach
        </select><br><br>

        <label>Keahlian Dibutuhkan:</label><br>
        <select name="id_keahlian">
            <option value="">-- Pilih --</option>
            @foreach($keahlian as $k)
                <option value="{{ $k->id_keahlian }}" {{ old('id_keahlian') == $k->id_keahlian ? 'selected' : '' }}>{{ $k->nama_keahlian }}</option>
            @endforeach
        </select><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi">{{ old('deskripsi') }}</textarea><br><br>

        <label>Upah (Rp):</label><br>
        <input type="number" step="0.01" name="upah" value="{{ old('upah') }}"><br><br>

        <label>Lokasi:</label><br>
        <textarea name="lokasi">{{ old('lokasi') }}</textarea><br><br>

        <label>Latitude:</label><br>
        <input type="text" name="latitude" value="{{ old('latitude') }}" placeholder="contoh: -7.28167"><br><br>

        <label>Longitude:</label><br>
        <input type="text" name="longitude" value="{{ old('longitude') }}" placeholder="contoh: 112.7383"><br><br>

        <label>Tanggal Pengerjaan:</label><br>
        <input type="date" name="tanggal_pengerjaan" value="{{ old('tanggal_pengerjaan') }}"><br><br>

        <label>Status Pekerjaan:</label><br>
        <select name="status_pekerjaan">
            <option value="tersedia" {{ old('status_pekerjaan') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="sedang_dikerjakan" {{ old('status_pekerjaan') == 'sedang_dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
            <option value="selesai" {{ old('status_pekerjaan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select><br><br>

        <label>Tanggal Posting:</label><br>
        <input type="date" name="tanggal_posting" value="{{ old('tanggal_posting') }}"><br><br>

        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="{{ route('pekerjaan.index') }}">Kembali ke daftar</a>
</body>
</html>