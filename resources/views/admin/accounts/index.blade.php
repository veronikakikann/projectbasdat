<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Data {{ str_replace('_', ' ', $type) }}</title></head>
<body>
<h1>Data {{ ucwords(str_replace('_', ' ', $type)) }}</h1>
<a href="{{ route('admin.dashboard') }}">Dashboard</a> | <a href="{{ route($type . '.create') }}">Tambah Akun</a>
@if(session('success'))<p style="color:green">{{ session('success') }}</p>@endif
@if(session('error'))<p style="color:red">{{ session('error') }}</p>@endif
<table border="1" cellpadding="8">
<thead><tr><th>NIK</th><th>Nama</th><th>Email</th><th>Verifikasi</th><th>Status Akun</th><th>Admin Verifikator</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($accounts as $account)
<tr>
<td>{{ $account->nik }}</td><td>{{ $account->nama }}</td><td>{{ $account->email }}</td>
<td>{{ $account->status_verifikasi }}</td><td>{{ $account->status_akun }}</td><td>{{ $account->admin->nama ?? '-' }}</td>
<td>
<a href="{{ route($type . '.edit', $account) }}">Edit / Verifikasi</a>
@if($account->file_ktp)<a href="{{ route('admin.akun.ktp', [$type, $account->getKey()]) }}">KTP</a>@endif
<form action="{{ route($type . '.destroy', $account) }}" method="POST" style="display:inline">
@csrf @method('DELETE')
<button type="submit" onclick="return confirm('Hapus akun ini? Akun yang punya riwayat harus dinonaktifkan.')">Hapus</button>
</form>
</td></tr>
@empty<tr><td colspan="7">Belum ada akun.</td></tr>@endforelse
</tbody></table>
</body></html>
