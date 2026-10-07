<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Kelola {{ str_replace('_', ' ', $type) }}</title></head>
<body>
@php($editing = isset($account) && $account->exists)
<h1>{{ $editing ? 'Edit' : 'Tambah' }} {{ ucwords(str_replace('_', ' ', $type)) }}</h1>
<a href="{{ route($type . '.index') }}">Kembali ke daftar</a>
@if($errors->any())<ul style="color:red">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif
<form action="{{ $editing ? route($type . '.update', $account) : route($type . '.store') }}" method="POST">
@csrf
@if($editing) @method('PUT') @endif
<p><label>NIK <input name="nik" value="{{ old('nik', $account->nik ?? '') }}" maxlength="16" {{ $editing ? 'readonly' : 'required' }}></label></p>
<p><label>Nama <input name="nama" value="{{ old('nama', $account->nama ?? '') }}" maxlength="100" required></label></p>
<p><label>Alamat <textarea name="alamat" required>{{ old('alamat', $account->alamat ?? '') }}</textarea></label></p>
<p><label>No. Telepon <input name="no_telpon" value="{{ old('no_telpon', $account->no_telpon ?? '') }}" maxlength="15" required></label></p>
<p><label>Email <input type="email" name="email" value="{{ old('email', $account->email ?? '') }}" required></label></p>
<p><label>Password {{ $editing ? '(kosongkan jika tidak diubah)' : '' }} <input type="password" name="password" minlength="6" autocomplete="new-password" {{ $editing ? '' : 'required' }}></label></p>
<p><label>Konfirmasi Password <input type="password" name="password_confirmation" autocomplete="new-password" {{ $editing ? '' : 'required' }}></label></p>
@if($type === 'pencari_kerja')
<p><label>Latitude <input type="number" step="any" name="latitude" min="-90" max="90" value="{{ old('latitude', $account->latitude ?? '') }}"></label></p>
<p><label>Longitude <input type="number" step="any" name="longitude" min="-180" max="180" value="{{ old('longitude', $account->longitude ?? '') }}"></label></p>
@endif
<p><label>Status Verifikasi <select name="status_verifikasi" required>
@foreach(['menunggu','terverifikasi','ditolak'] as $status)<option value="{{ $status }}" @selected(old('status_verifikasi', $account->status_verifikasi ?? 'menunggu') === $status)>{{ ucfirst($status) }}</option>@endforeach
</select></label></p>
<p><label>Status Akun <select name="status_akun" required>
@foreach(['aktif','nonaktif'] as $status)<option value="{{ $status }}" @selected(old('status_akun', $account->status_akun ?? 'aktif') === $status)>{{ ucfirst($status) }}</option>@endforeach
</select></label></p>
<p><label>Tanggal Daftar <input type="date" name="tanggal_daftar" value="{{ old('tanggal_daftar', $account->tanggal_daftar ?? date('Y-m-d')) }}" required></label></p>
@if($editing && $account->file_ktp)
<p><a href="{{ route('admin.akun.ktp', [$type, $account->getKey()]) }}" target="_blank" rel="noopener">Lihat KTP untuk verifikasi</a></p>
@endif
<p>Keputusan verifikasi dicatat atas nama admin yang sedang login.</p>
<button type="submit">Simpan</button>
</form>
</body></html>
