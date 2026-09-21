<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pemberi Kerja</title>
</head>
<body>
    <h1>Tambah Pemberi Kerja</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pemberi_kerja.store') }}" method="POST">
        @csrf

        <label>NIK:</label><br>
        <input type="text" name="nik" value="{{ old('nik') }}"><br><br>

        <label>Nama:</label><br>
        <input type="text" name="nama" value="{{ old('nama') }}"><br><br>

        <label>Alamat:</label><br>
        <textarea name="alamat">{{ old('alamat') }}</textarea><br><br>

        <label>No. Telpon:</label><br>
        <input type="text" name="no_telpon" value="{{ old('no_telpon') }}"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email') }}"><br><br>

        <label>Password:</label><br>
        <input type="password" name="password"><br><br>

        <label>Status Verifikasi:</label><br>
        <select name="status_verifikasi">
            <option value="menunggu" {{ old('status_verifikasi') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="terverifikasi" {{ old('status_verifikasi') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
            <option value="ditolak" {{ old('status_verifikasi') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select><br><br>

        <label>Diverifikasi Oleh Admin:</label><br>
        <select name="id_admin">
            <option value="">-- Belum ada --</option>
            @foreach($admins as $admin)
                <option value="{{ $admin->id_admin }}" {{ old('id_admin') == $admin->id_admin ? 'selected' : '' }}>{{ $admin->nama }}</option>
            @endforeach
        </select><br><br>

        <label>Tanggal Daftar:</label><br>
        <input type="date" name="tanggal_daftar" value="{{ old('tanggal_daftar') }}"><br><br>

        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="{{ route('pemberi_kerja.index') }}">Kembali ke daftar</a>
</body>
</html>