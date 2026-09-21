<!DOCTYPE html>
<html>
<head>
    <title>Edit Admin</title>
</head>
<body>
    <h1>Edit Admin</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.update', $admin->id_admin) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama:</label><br>
        <input type="text" name="nama" value="{{ old('nama', $admin->nama) }}"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email', $admin->email) }}"><br><br>

        <label>Password (kosongkan jika tidak diubah):</label><br>
        <input type="password" name="password"><br><br>

        <label>Tanggal Bergabung:</label><br>
        <input type="date" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', $admin->tanggal_bergabung) }}"><br><br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="{{ route('admin.index') }}">Kembali ke daftar</a>
</body>
</html>