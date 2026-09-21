<!DOCTYPE html>
<html>
<head>
    <title>Tambah Keahlian</title>
</head>
<body>
    <h1>Tambah Keahlian</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('keahlian.store') }}" method="POST">
        @csrf

        <label>Nama Keahlian:</label><br>
        <input type="text" name="nama_keahlian" value="{{ old('nama_keahlian') }}"><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi">{{ old('deskripsi') }}</textarea><br><br>

        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="{{ route('keahlian.index') }}">Kembali ke daftar</a>
</body>
</html>