<!DOCTYPE html>
<html>
<head>
    <title>Data Pemberi Kerja</title>
</head>
<body>
    <h1>Data Pemberi Kerja</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('pemberi_kerja.create') }}">Tambah Pemberi Kerja</a>

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
        @foreach($pemberiKerja as $pk)
        <tr>
            <td>{{ $pk->id_pemberi }}</td>
            <td>{{ $pk->nik }}</td>
            <td>{{ $pk->nama }}</td>
            <td>{{ $pk->email }}</td>
            <td>{{ $pk->no_telpon }}</td>
            <td>{{ $pk->status_verifikasi }}</td>
            <td>{{ $pk->admin->nama ?? '-' }}</td>
            <td>{{ $pk->tanggal_daftar }}</td>
            <td>
                <a href="{{ route('pemberi_kerja.edit', $pk->id_pemberi) }}">Edit</a>
                <form action="{{ route('pemberi_kerja.destroy', $pk->id_pemberi) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
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