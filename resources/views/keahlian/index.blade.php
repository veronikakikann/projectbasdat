<!DOCTYPE html>
<html>
<head>
    <title>Data Keahlian</title>
</head>
<body>
    <h1>Data Keahlian</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('keahlian.create') }}">Tambah Keahlian</a>

    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 10px;">
        <tr>
            <th>ID</th>
            <th>Nama Keahlian</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
        @foreach($keahlian as $k)
        <tr>
            <td>{{ $k->id_keahlian }}</td>
            <td>{{ $k->nama_keahlian }}</td>
            <td>{{ $k->deskripsi }}</td>
            <td>
                <a href="{{ route('keahlian.edit', $k->id_keahlian) }}">Edit</a>
                <form action="{{ route('keahlian.destroy', $k->id_keahlian) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
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