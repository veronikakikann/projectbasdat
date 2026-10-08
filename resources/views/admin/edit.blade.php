@extends('admin.layout')
@section('title', 'Edit Admin')
@section('content')
<div class="panel">
    @if($errors->any())
        <ul class="errors">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    @endif
    <form action="{{ route('admin.update', $admin) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field"><label for="nama">Nama</label><input type="text" id="nama" name="nama" value="{{ old('nama', $admin->nama) }}" maxlength="100" required></div>
            <div class="field"><label for="email">Email</label><input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" required></div>
            <div class="field"><label for="password">Password baru</label><input type="password" id="password" name="password" minlength="6" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password"></div>
            <div class="field"><label for="password_confirmation">Konfirmasi Password</label><input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"></div>
            <div class="field"><label for="tanggal_bergabung">Tanggal Bergabung</label><input type="date" id="tanggal_bergabung" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', $admin->tanggal_bergabung) }}" required></div>
        </div>
        <div class="actions" style="margin-top:1.25rem">
            <button type="submit" class="btn">Update</button>
            <a href="{{ route('admin.index') }}" class="btn btn-ghost">Kembali ke daftar</a>
        </div>
    </form>
</div>
@endsection
