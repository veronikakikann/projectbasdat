@extends('admin.layout')
@php
    $editing = isset($account) && $account->exists;
    $judul = ucwords(str_replace('_', ' ', $type));
@endphp
@section('title', ($editing ? 'Edit ' : 'Tambah ') . $judul)
@section('content')
<div class="panel">
    @if($errors->any())
        <ul class="errors">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    @endif

    <form action="{{ $editing ? route($type . '.update', $account) : route($type . '.store') }}" method="POST">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="form-grid">
            <div class="field"><label for="nik">NIK</label><input id="nik" name="nik" value="{{ old('nik', $account->nik ?? '') }}" maxlength="16" {{ $editing ? 'readonly' : 'required' }}>@if($editing)<div class="hint">NIK tidak bisa diubah.</div>@endif</div>
            <div class="field"><label for="nama">Nama</label><input id="nama" name="nama" value="{{ old('nama', $account->nama ?? '') }}" maxlength="100" required></div>
            <div class="field"><label for="email">Email</label><input type="email" id="email" name="email" value="{{ old('email', $account->email ?? '') }}" required></div>
            <div class="field"><label for="no_telpon">No. Telepon</label><input id="no_telpon" name="no_telpon" value="{{ old('no_telpon', $account->no_telpon ?? '') }}" maxlength="15" required></div>
            <div class="field field-full"><label for="alamat">Alamat</label><textarea id="alamat" name="alamat" required>{{ old('alamat', $account->alamat ?? '') }}</textarea></div>
            <div class="field"><label for="password">Password {{ $editing ? '(kosongkan jika tidak diubah)' : '' }}</label><input type="password" id="password" name="password" minlength="6" autocomplete="new-password" {{ $editing ? '' : 'required' }}></div>
            <div class="field"><label for="password_confirmation">Konfirmasi Password</label><input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" {{ $editing ? '' : 'required' }}></div>
            @if($type === 'pencari_kerja')
                <div class="field"><label for="latitude">Latitude</label><input type="number" step="any" id="latitude" name="latitude" min="-90" max="90" value="{{ old('latitude', $account->latitude ?? '') }}"></div>
                <div class="field"><label for="longitude">Longitude</label><input type="number" step="any" id="longitude" name="longitude" min="-180" max="180" value="{{ old('longitude', $account->longitude ?? '') }}"></div>
            @endif
            <div class="field"><label for="status_verifikasi">Status Verifikasi</label>
                <select id="status_verifikasi" name="status_verifikasi" required>
                    @foreach(['menunggu', 'terverifikasi', 'ditolak'] as $status)
                        <option value="{{ $status }}" @selected(old('status_verifikasi', $account->status_verifikasi ?? 'menunggu') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field"><label for="status_akun">Status Akun</label>
                <select id="status_akun" name="status_akun" required>
                    @foreach(['aktif', 'nonaktif'] as $status)
                        <option value="{{ $status }}" @selected(old('status_akun', $account->status_akun ?? 'aktif') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field"><label for="tanggal_daftar">Tanggal Daftar</label><input type="date" id="tanggal_daftar" name="tanggal_daftar" value="{{ old('tanggal_daftar', $account->tanggal_daftar ?? date('Y-m-d')) }}" required></div>
        </div>

        @if($editing && $account->file_ktp)
            <p style="margin-top:1rem"><a href="{{ route('admin.akun.ktp', [$type, $account->getKey()]) }}" target="_blank" rel="noopener" class="btn btn-sm btn-ghost">Lihat KTP untuk verifikasi</a></p>
        @endif
        <p class="hint" style="margin-top:.75rem">Keputusan verifikasi dicatat atas nama admin yang sedang login, dan pengguna otomatis dapat notifikasi.</p>

        <div class="actions" style="margin-top:1rem">
            <button type="submit" class="btn">Simpan</button>
            <a href="{{ route($type . '.index') }}" class="btn btn-ghost">Kembali ke daftar</a>
        </div>
    </form>
</div>
@endsection
