<!DOCTYPE html>
<html>
<head>
    <title>Edit Pemberi Kerja</title>
</head>
<body>
    <h1>Edit Pemberi Kerja</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pemberi_kerja.update', $pemberiKerja->id_pemberi) }}" method="POST">
        @csrf
        @method('PUT')

        <label>NIK:</label><br>
        <input type="text" name="nik" value="{{ old('nik', $pemberiKerja->nik) }}"><br><br>

        <label>Nama:</label><br>
        <input type="text" name="nama" value="{{ old('nama', $pemberiKerja->nama) }}"><br><br>

        <label>Alamat:</label><br>
        <textarea name="alamat">{{ old('alamat', $pemberiKerja->alamat) }}</textarea><br><br>

        <label>No. Telpon:</label><br>
        <input type="text" name="no_telpon" value="{{ old('no_telpon', $pemberiKerja->no_telpon) }}"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email', $pemberiKerja->email) }}"><br><br>

        <label>Password (kosongkan jika tidak diubah):</label><br>
        <input type="password" name="password"><br><br>

        <label>Status Verifikasi:</label><br>
        <select name="status_verifikasi">
            <option value="menunggu" {{ $pemberiKerja->status_verifikasi == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="terverifikasi" {{ $pemberiKerja->status_verifikasi == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
            <option value="ditolak" {{ $pemberiKerja->status_verifikasi == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select><br><br>

        <label>Diverifikasi Oleh Admin:</label><br>
        <select name="id_admin">
            <option value="">-- Belum ada --</option>
            @foreach($admins as $admin)
                <option value="{{ $admin->id_admin }}" {{ $pemberiKerja->id_admin == $admin->id_admin ? 'selected' : '' }}>{{ $admin->nama }}</option>
            @endforeach
        </select><br><br>

        <label>Tanggal Daftar:</label><br>
        <input type="date" name="tanggal_daftar" value="{{ old('tanggal_daftar', $pemberiKerja->tanggal_daftar) }}"><br><br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="{{ route('pemberi_kerja.index') }}">Kembali ke daftar</a>
</body>
</html>