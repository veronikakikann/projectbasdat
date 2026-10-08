@extends('admin.layout')
@section('title', 'Tambah Admin')
@section('content')
<div class="panel">
    @if($errors->any())
        <ul class="errors">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    @endif
    <form action="{{ route('admin.store') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div class="field"><label for="nama">Nama</label><input type="text" id="nama" name="nama" value="{{ old('nama') }}" maxlength="100" required></div>
            <div class="field"><label for="email">Email</label><input type="email" id="email" name="email" value="{{ old('email') }}" required></div>
            <div class="field"><label for="password">Password</label><input type="password" id="password" name="password" minlength="6" autocomplete="new-password" required></div>
            <div class="field"><label for="password_confirmation">Konfirmasi Password</label><input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required></div>
            <div class="field"><label for="tanggal_bergabung">Tanggal Bergabung</label><input type="date" id="tanggal_bergabung" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', date('Y-m-d')) }}" required></div>
        </div>
        <div class="actions" style="margin-top:1.25rem">
            <button type="submit" class="btn">Simpan</button>
            <a href="{{ route('admin.index') }}" class="btn btn-ghost">Kembali ke daftar</a>
        </div>
    </form>
</div>
@endsection
