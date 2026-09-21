<!DOCTYPE html>
<html>
<head>
    <title>Edit Pekerjaan</title>
</head>
<body>
    <h1>Edit Pekerjaan</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pekerjaan.update', $pekerjaan->id_pekerjaan) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Pemberi Kerja:</label><br>
        <select name="id_pemberi">
            @foreach($pemberiKerja as $pk)
                <option value="{{ $pk->id_pemberi }}" {{ $pekerjaan->id_pemberi == $pk->id_pemberi ? 'selected' : '' }}>{{ $pk->nama }}</option>
            @endforeach
        </select><br><br>

        <label>Keahlian Dibutuhkan:</label><br>
        <select name="id_keahlian">
            @foreach($keahlian as $k)
                <option value="{{ $k->id_keahlian }}" {{ $pekerjaan->id_keahlian == $k->id_keahlian ? 'selected' : '' }}>{{ $k->nama_keahlian }}</option>
            @endforeach
        </select><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi">{{ old('deskripsi', $pekerjaan->deskripsi) }}</textarea><br><br>

        <label>Upah (Rp):</label><br>
        <input type="number" step="0.01" name="upah" value="{{ old('upah', $pekerjaan->upah) }}"><br><br>

        <label>Lokasi:</label><br>
        <textarea name="lokasi">{{ old('lokasi', $pekerjaan->lokasi) }}</textarea><br><br>

        <label>Latitude:</label><br>
        <input type="text" name="latitude" value="{{ old('latitude', $pekerjaan->latitude) }}"><br><br>

        <label>Longitude:</label><br>
        <input type="text" name="longitude" value="{{ old('longitude', $pekerjaan->longitude) }}"><br><br>

        <label>Tanggal Pengerjaan:</label><br>
        <input type="date" name="tanggal_pengerjaan" value="{{ old('tanggal_pengerjaan', $pekerjaan->tanggal_pengerjaan) }}"><br><br>

        <label>Status Pekerjaan:</label><br>
        <select name="status_pekerjaan">
            <option value="tersedia" {{ $pekerjaan->status_pekerjaan == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="sedang_dikerjakan" {{ $pekerjaan->status_pekerjaan == 'sedang_dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
            <option value="selesai" {{ $pekerjaan->status_pekerjaan == 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select><br><br>

        <label>Tanggal Posting:</label><br>
        <input type="date" name="tanggal_posting" value="{{ old('tanggal_posting', $pekerjaan->tanggal_posting) }}"><br><br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="{{ route('pekerjaan.index') }}">Kembali ke daftar</a>
</body>
</html>