<!DOCTYPE html>
<html>
<head>
    <title>Data Pekerjaan</title>
</head>
<body>
    <h1>Data Pekerjaan</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('pekerjaan.create') }}">Tambah Pekerjaan</a>

    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 10px;">
        <tr>
            <th>ID</th>
            <th>Pemberi Kerja</th>
            <th>Keahlian Dibutuhkan</th>
            <th>Deskripsi</th>
            <th>Upah</th>
            <th>Lokasi</th>
            <th>Tanggal Pengerjaan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        @foreach($pekerjaan as $p)
        <tr>
            <td>{{ $p->id_pekerjaan }}</td>
            <td>{{ $p->pemberiKerja->nama ?? '-' }}</td>
            <td>{{ $p->keahlian->nama_keahlian ?? '-' }}</td>
            <td>{{ $p->deskripsi }}</td>
            <td>{{ $p->upah }}</td>
            <td>{{ $p->lokasi }}</td>
            <td>{{ $p->tanggal_pengerjaan }}</td>
            <td>{{ $p->status_pekerjaan }}</td>
            <td>
                <a href="{{ route('pekerjaan.edit', $p->id_pekerjaan) }}">Edit</a>
                <form action="{{ route('pekerjaan.destroy', $p->id_pekerjaan) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>