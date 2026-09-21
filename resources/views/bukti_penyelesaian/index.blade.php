<!DOCTYPE html>
<html>
<head><title>Data Bukti Penyelesaian</title></head>
<body>
    <h1>Data Bukti Penyelesaian</h1>
    @if(session('success'))<p style="color: green;">{{ session('success') }}</p>@endif
    <a href="{{ route('bukti_penyelesaian.create') }}">Tambah Bukti Penyelesaian</a>
    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 10px;">
        <tr><th>ID</th><th>Lamaran</th><th>Foto Bukti Kerja</th><th>Foto Bukti Bayar</th><th>Catatan</th><th>Tanggal Upload</th><th>Aksi</th></tr>
        @foreach($buktiPenyelesaian as $b)
        <tr>
            <td>{{ $b->id_bukti }}</td>
            <td>{{ $b->id_lamaran }}</td>
            <td>{{ $b->foto_bukti_kerja }}</td>
            <td>{{ $b->foto_bukti_bayar }}</td>
            <td>{{ $b->catatan }}</td>
            <td>{{ $b->tanggal_upload }}</td>
            <td>
                <a href="{{ route('bukti_penyelesaian.edit', $b->id_bukti) }}">Edit</a>
                <form action="{{ route('bukti_penyelesaian.destroy', $b->id_bukti) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>