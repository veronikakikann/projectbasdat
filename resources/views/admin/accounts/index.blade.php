@extends('admin.layout')
@php($judul = ucwords(str_replace('_', ' ', $type)))
@section('title', $judul)
@section('content')
<div class="panel">
    <div class="toolbar">
        <div class="tabs" style="margin-bottom:0">
            <a href="{{ route($type . '.index') }}" class="tab {{ $status ? '' : 'is-active' }}">Semua</a>
            @foreach(['menunggu' => 'Menunggu', 'terverifikasi' => 'Terverifikasi', 'ditolak' => 'Ditolak'] as $key => $label)
                <a href="{{ route($type . '.index', ['status' => $key]) }}" class="tab {{ $status === $key ? 'is-active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
        <a href="{{ route($type . '.create') }}" class="btn">+ Tambah Akun</a>
    </div>

    <div class="table-wrap">
        <table class="tbl">
            <thead>
                <tr><th>NIK</th><th>Nama</th><th>Email</th><th>Verifikasi</th><th>Status Akun</th><th>Diverifikasi Oleh</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($accounts as $account)
                <tr>
                    <td>{{ $account->nik }}</td>
                    <td>{{ $account->nama }}</td>
                    <td>{{ $account->email }}</td>
                    <td><span class="badge badge-{{ $account->status_verifikasi }}">{{ ucfirst($account->status_verifikasi) }}</span></td>
                    <td><span class="badge badge-{{ $account->status_akun }}">{{ ucfirst($account->status_akun) }}</span></td>
                    <td>{{ $account->admin->nama ?? '-' }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route($type . '.edit', $account) }}" class="btn btn-sm">Edit / Verifikasi</a>
                            @if($account->file_ktp)
                                <a href="{{ route('admin.akun.ktp', [$type, $account->getKey()]) }}" target="_blank" rel="noopener" class="btn btn-sm btn-ghost">Lihat KTP</a>
                            @endif
                            <form action="{{ route($type . '.destroy', $account) }}" method="POST" onsubmit="return confirm('Hapus akun ini? Akun yang punya riwayat harus dinonaktifkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty-row">Belum ada akun{{ $status ? ' dengan status ' . $status : '' }}.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
