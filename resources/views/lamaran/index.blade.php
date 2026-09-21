<!DOCTYPE html>
<html>
<head><title>Data Lamaran</title></head>
<body>
    <h1>Data Lamaran</h1>
    @if(session('success'))<p style="color: green;">{{ session('success') }}</p>@endif
    <a href="{{ route('lamaran.create') }}">Tambah Lamaran</a>
    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 10px;">
        <tr><th>ID</th><th>Pekerjaan</th><th>Pencari Kerja</th><th>Status</th><th>Tanggal Submit</th><th>Aksi</th></tr>
        @foreach($lamaran as $l)
        <tr>
            <td>{{ $l->id_lamaran }}</td>
            <td>{{ $l->pekerjaan->deskripsi ?? '-' }}</td>
            <td>{{ $l->pencariKerja->nama ?? '-' }}</td>
            <td>{{ $l->status_lamaran }}</td>
            <td>{{ $l->tanggal_submit }}</td>
            <td>
                <a href="{{ route('lamaran.edit', $l->id_lamaran) }}">Edit</a>
                <form action="{{ route('lamaran.destroy', $l->id_lamaran) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>