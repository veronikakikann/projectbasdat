@extends('admin.layout')
@section('title', 'Data Keahlian')
@section('content')
<div class="panel">
    <div class="toolbar">
        <span class="muted">Kategori besar keahlian (Tukang AC, Tukang Bangunan, ART, dll) yang dipilih admin saat verifikasi.</span>
        <a href="{{ route('keahlian.create') }}" class="btn">+ Tambah Keahlian</a>
    </div>
    <div class="table-wrap">
        <table class="tbl">
            <thead>
                <tr><th>ID</th><th>Nama Keahlian</th><th>Deskripsi</th><th>Dipakai Pencari</th><th>Dipakai Lowongan</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($keahlian as $item)
                <tr>
                    <td>{{ $item->id_keahlian }}</td>
                    <td><strong>{{ $item->nama_keahlian }}</strong></td>
                    <td>{{ $item->deskripsi ?: '-' }}</td>
                    <td>{{ $item->pengajuan_pencari_count }}</td>
                    <td>{{ $item->pekerjaan_count }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('keahlian.edit', $item) }}" class="btn btn-sm btn-ghost">Edit</a>
                            <form action="{{ route('keahlian.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus keahlian ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty-row">Belum ada data keahlian.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
