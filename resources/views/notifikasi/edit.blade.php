<!DOCTYPE html>
<html>
<head><title>Edit Notifikasi</title></head>
<body>
    <h1>Edit Notifikasi</h1>
    @if($errors->any())<div style="color: red;"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('notifikasi.update', $notifikasi->id_notifikasi) }}" method="POST">
        @csrf @method('PUT')
        <label>ID User:</label><br>
        <input type="number" name="id_user" value="{{ old('id_user', $notifikasi->id_user) }}"><br><br>
        <label>Tipe User:</label><br>
        <select name="tipe_user">
            <option value="pencari_kerja" {{ $notifikasi->tipe_user == 'pencari_kerja' ? 'selected' : '' }}>Pencari Kerja</option>
            <option value="pemberi_kerja" {{ $notifikasi->tipe_user == 'pemberi_kerja' ? 'selected' : '' }}>Pemberi Kerja</option>
        </select><br><br>
        <label>Isi Pesan:</label><br>
        <textarea name="isi_pesan">{{ old('isi_pesan', $notifikasi->isi_pesan) }}</textarea><br><br>
        <label>Status Baca:</label><br>
        <select name="status_baca">
            <option value="0" {{ $notifikasi->status_baca == 0 ? 'selected' : '' }}>Belum dibaca</option>
            <option value="1" {{ $notifikasi->status_baca == 1 ? 'selected' : '' }}>Sudah dibaca</option>
        </select><br><br>
        <label>Tanggal:</label><br>
        <input type="datetime-local" name="tanggal" value="{{ old('tanggal', $notifikasi->tanggal) }}"><br><br>
        <button type="submit">Update</button>
    </form>
    <br><a href="{{ route('notifikasi.index') }}">Kembali ke daftar</a>
</body>
</html>