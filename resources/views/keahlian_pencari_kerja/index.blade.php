<!DOCTYPE html>
<html>
<head>
    <title>Data Keahlian Pencari Kerja</title>
</head>
<body>
    <h1>Data Keahlian Pencari Kerja</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('keahlian_pencari_kerja.create') }}">Tambah Data</a>

    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 10px;">
        <tr>
            <th>Pencari Kerja</th>
            <th>Keahlian</th>
            <th>File Rekomendasi</th>
            <th>Status Verifikasi</th>
            <th>Tanggal Upload</th>
            <th>Aksi</th>
        </tr>
        @foreach($data as $d)
        <tr>
            <td>{{ $d->nama_pencari }}</td>
            <td>{{ $d->nama_keahlian }}</td>
            <td>{{ $d->file_surat_rekomendasi }}</td>
            <td>{{ $d->status_verifikasi_keahlian }}</td>
            <td>{{ $d->tanggal_upload }}</td>
            <td>
                <a href="{{ route('keahlian_pencari_kerja.edit', [$d->id_pencari, $d->id_keahlian]) }}">Edit</a>
                <form action="{{ route('keahlian_pencari_kerja.destroy', [$d->id_pencari, $d->id_keahlian]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
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