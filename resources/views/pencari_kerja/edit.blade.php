<!DOCTYPE html>
<html>
<head>
    <title>Edit Pencari Kerja</title>
</head>
<body>
    <h1>Edit Pencari Kerja</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pencari_kerja.update', $pencariKerja->id_pencari) }}" method="POST">
        @csrf
        @method('PUT')

        <label>NIK:</label><br>
        <input type="text" name="nik" value="{{ old('nik', $pencariKerja->nik) }}"><br><br>

        <label>Nama:</label><br>
        <input type="text" name="nama" value="{{ old('nama', $pencariKerja->nama) }}"><br><br>

        <label>Alamat:</label><br>
        <textarea name="alamat">{{ old('alamat', $pencariKerja->alamat) }}</textarea><br><br>

        <label>No. Telpon:</label><br>
        <input type="text" name="no_telpon" value="{{ old('no_telpon', $pencariKerja->no_telpon) }}"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email', $pencariKerja->email) }}"><br><br>

        <label>Password (kosongkan jika tidak diubah):</label><br>
        <input type="password" name="password"><br><br>

        <label>Latitude:</label><br>
        <input type="text" name="latitude" value="{{ old('latitude', $pencariKerja->latitude) }}"><br><br>

        <label>Longitude:</label><br>
        <input type="text" name="longitude" value="{{ old('longitude', $pencariKerja->longitude) }}"><br><br>

        <label>Status Verifikasi:</label><br>
        <select name="status_verifikasi">
            <option value="menunggu" {{ $pencariKerja->status_verifikasi == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="terverifikasi" {{ $pencariKerja->status_verifikasi == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
            <option value="ditolak" {{ $pencariKerja->status_verifikasi == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select><br><br>

        <label>Diverifikasi Oleh Admin:</label><br>
        <select name="id_admin">
            <option value="">-- Belum ada --</option>
            @foreach($admins as $admin)
                <option value="{{ $admin->id_admin }}" {{ $pencariKerja->id_admin == $admin->id_admin ? 'selected' : '' }}>{{ $admin->nama }}</option>
            @endforeach
        </select><br><br>

        <label>Tanggal Daftar:</label><br>
        <input type="date" name="tanggal_daftar" value="{{ old('tanggal_daftar', $pencariKerja->tanggal_daftar) }}"><br><br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="{{ route('pencari_kerja.index') }}">Kembali ke daftar</a>
</body>
</html>