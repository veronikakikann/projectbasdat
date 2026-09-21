<!DOCTYPE html>
<html>
<head>
    <title>Tambah Keahlian Pencari Kerja</title>
</head>
<body>
    <h1>Tambah Keahlian Pencari Kerja</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('keahlian_pencari_kerja.store') }}" method="POST">
        @csrf

        <label>Pencari Kerja:</label><br>
        <select name="id_pencari">
            <option value="">-- Pilih --</option>
            @foreach($pencariKerja as $pk)
                <option value="{{ $pk->id_pencari }}" {{ old('id_pencari') == $pk->id_pencari ? 'selected' : '' }}>{{ $pk->nama }}</option>
            @endforeach
        </select><br><br>

        <label>Keahlian:</label><br>
        <select name="id_keahlian">
            <option value="">-- Pilih --</option>
            @foreach($keahlian as $k)
                <option value="{{ $k->id_keahlian }}" {{ old('id_keahlian') == $k->id_keahlian ? 'selected' : '' }}>{{ $k->nama_keahlian }}</option>
            @endforeach
        </select><br><br>

        <label>File Surat Rekomendasi:</label><br>
        <input type="text" name="file_surat_rekomendasi" value="{{ old('file_surat_rekomendasi') }}" placeholder="nama_file.pdf"><br><br>

        <label>Status Verifikasi Keahlian:</label><br>
        <select name="status_verifikasi_keahlian">
            <option value="menunggu" {{ old('status_verifikasi_keahlian') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="terverifikasi" {{ old('status_verifikasi_keahlian') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
            <option value="ditolak" {{ old('status_verifikasi_keahlian') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select><br><br>

        <label>Tanggal Upload:</label><br>
        <input type="date" name="tanggal_upload" value="{{ old('tanggal_upload') }}"><br><br>

        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="{{ route('keahlian_pencari_kerja.index') }}">Kembali ke daftar</a>
</body>
</html>