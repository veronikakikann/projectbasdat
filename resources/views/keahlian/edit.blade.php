<!DOCTYPE html>
<html>
<head>
    <title>Edit Keahlian</title>
</head>
<body>
    <h1>Edit Keahlian</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('keahlian.update', $keahlian->id_keahlian) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama Keahlian:</label><br>
        <input type="text" name="nama_keahlian" value="{{ old('nama_keahlian', $keahlian->nama_keahlian) }}"><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi">{{ old('deskripsi', $keahlian->deskripsi) }}</textarea><br><br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="{{ route('keahlian.index') }}">Kembali ke daftar</a>
</body>
</html>