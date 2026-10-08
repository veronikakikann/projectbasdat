@extends('admin.layout')
@php($editing = isset($keahlian) && $keahlian->exists)
@section('title', $editing ? 'Edit Keahlian' : 'Tambah Keahlian')
@section('content')
<div class="panel">
    @if($errors->any())
        <ul class="errors">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    @endif
    <form action="{{ $editing ? route('keahlian.update', $keahlian) : route('keahlian.store') }}" method="POST">
        @csrf
        @if($editing) @method('PUT') @endif
        <div class="form-grid">
            <div class="field"><label for="nama_keahlian">Nama Keahlian</label><input id="nama_keahlian" name="nama_keahlian" value="{{ old('nama_keahlian', $keahlian->nama_keahlian ?? '') }}" maxlength="100" placeholder="Contoh: Tukang AC" required></div>
            <div class="field field-full"><label for="deskripsi">Deskripsi (opsional)</label><textarea id="deskripsi" name="deskripsi">{{ old('deskripsi', $keahlian->deskripsi ?? '') }}</textarea></div>
        </div>
        <div class="actions" style="margin-top:1.25rem">
            <button type="submit" class="btn">Simpan</button>
            <a href="{{ route('keahlian.index') }}" class="btn btn-ghost">Kembali ke daftar</a>
        </div>
    </form>
</div>
@endsection
