@extends('admin.layout')
@section('title', 'Akun Admin')
@section('content')
<div class="panel">
    <div class="toolbar">
        <span class="muted">Kelola akun admin yang boleh masuk ke dashboard ini.</span>
        <a href="{{ route('admin.create') }}" class="btn">+ Tambah Admin</a>
    </div>
    <div class="table-wrap">
        <table class="tbl">
            <thead>
                <tr><th>ID</th><th>Nama</th><th>Email</th><th>Tanggal Bergabung</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($admins as $admin)
                <tr>
                    <td>{{ $admin->id_admin }}</td>
                    <td>{{ $admin->nama }} @if((int) session('user_id') === (int) $admin->id_admin)<span class="badge badge-aktif">Kamu</span>@endif</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->tanggal_bergabung }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('admin.edit', $admin) }}" class="btn btn-sm btn-ghost">Edit</a>
                            <form action="{{ route('admin.destroy', $admin) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty-row">Belum ada data admin.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
