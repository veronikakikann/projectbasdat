<!DOCTYPE html>
<html>
<head>
    <title>Data Pencari Kerja</title>
</head>
<body>
    <h1>Data Pencari Kerja</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('pencari_kerja.create') }}">Tambah Pencari Kerja</a>

    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 10px;">
        <tr>
            <th>ID</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No. Telpon</th>
            <th>Status Verifikasi</th>
            <th>Diverifikasi Oleh</th>
            <th>Tanggal Daftar</th>
            <th>Aksi</th>
        </tr>
        @foreach($pencariKerja as $pk)
        <tr>
            <td>{{ $pk->id_pencari }}</td>
            <td>{{ $pk->nik }}</td>
            <td>{{ $pk->nama }}</td>
            <td>{{ $pk->email }}</td>
            <td>{{ $pk->no_telpon }}</td>
            <td>{{ $pk->status_verifikasi }}</td>
            <td>{{ $pk->admin->nama ?? '-' }}</td>
            <td>{{ $pk->tanggal_daftar }}</td>
            <td>
                <a href="{{ route('pencari_kerja.edit', $pk->id_pencari) }}">Edit</a>
                <form action="{{ route('pencari_kerja.destroy', $pk->id_pencari) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
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