<!DOCTYPE html>
<html>
<head><title>Data Notifikasi</title></head>
<body>
    <h1>Data Notifikasi</h1>
    @if(session('success'))<p style="color: green;">{{ session('success') }}</p>@endif
    <a href="{{ route('notifikasi.create') }}">Tambah Notifikasi</a>
    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 10px;">
        <tr><th>ID</th><th>ID User</th><th>Tipe User</th><th>Isi Pesan</th><th>Status Baca</th><th>Tanggal</th><th>Aksi</th></tr>
        @foreach($notifikasi as $n)
        <tr>
            <td>{{ $n->id_notifikasi }}</td>
            <td>{{ $n->id_user }}</td>
            <td>{{ $n->tipe_user }}</td>
            <td>{{ $n->isi_pesan }}</td>
            <td>{{ $n->status_baca ? 'Sudah dibaca' : 'Belum dibaca' }}</td>
            <td>{{ $n->tanggal }}</td>
            <td>
                <a href="{{ route('notifikasi.edit', $n->id_notifikasi) }}">Edit</a>
                <form action="{{ route('notifikasi.destroy', $n->id_notifikasi) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>