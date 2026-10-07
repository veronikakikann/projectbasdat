<!DOCTYPE html>
<html>
<head>
    <title>Data Admin</title>
</head>
<body>

    <h1>Data Admin</h1>

    @if(session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    @if(session('error'))
        <p style="color: red;">
            {{ session('error') }}
        </p>
    @endif

    <a href="{{ route('admin.create') }}">
        Tambah Admin
    </a>

    <br><br>

    <table border="1" cellpadding="8" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Tanggal Bergabung</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($admins as $admin)
                <tr>
                    <td>{{ $admin->id_admin }}</td>
                    <td>{{ $admin->nama }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->tanggal_bergabung }}</td>

                    <td>
                        <a href="{{ route('admin.edit', $admin->id_admin) }}">
                            Edit
                        </a>

                        <form
                            action="{{ route('admin.destroy', $admin->id_admin) }}"
                            method="POST"
                            style="display: inline;"
                            onsubmit="return confirm('Yakin ingin menghapus admin ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        Belum ada data admin.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>