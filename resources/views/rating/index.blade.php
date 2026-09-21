<!DOCTYPE html>
<html>
<head><title>Data Rating</title></head>
<body>
    <h1>Data Rating</h1>
    @if(session('success'))<p style="color: green;">{{ session('success') }}</p>@endif
    <a href="{{ route('rating.create') }}">Tambah Rating</a>
    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 10px;">
        <tr><th>ID</th><th>Lamaran</th><th>Pemberi (ID)</th><th>Penerima (ID)</th><th>Arah</th><th>Skor</th><th>Kategori</th><th>Tanggal</th><th>Aksi</th></tr>
        @foreach($rating as $r)
        <tr>
            <td>{{ $r->id_rating }}</td>
            <td>{{ $r->id_lamaran }}</td>
            <td>{{ $r->pemberi_rating }}</td>
            <td>{{ $r->penerima_rating }}</td>
            <td>{{ $r->arah_rating }}</td>
            <td>{{ $r->skor }}</td>
            <td>{{ $r->kategori_komentar }}</td>
            <td>{{ $r->tanggal_rating }}</td>
            <td>
                <a href="{{ route('rating.edit', $r->id_rating) }}">Edit</a>
                <form action="{{ route('rating.destroy', $r->id_rating) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>