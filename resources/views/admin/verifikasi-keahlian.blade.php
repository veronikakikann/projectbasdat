@extends('admin.layout')
@section('title', 'Verifikasi Keahlian')
@section('content')
<div class="tabs">
    @foreach(['menunggu' => 'Menunggu', 'terverifikasi' => 'Terverifikasi', 'ditolak' => 'Ditolak', 'semua' => 'Semua'] as $key => $label)
        <a href="{{ route('admin.verifikasi-keahlian', ['status' => $key]) }}" class="tab {{ $filter === $key ? 'is-active' : '' }}">{{ $label }}<small>({{ $jumlah[$key] }})</small></a>
    @endforeach
</div>

@if($errors->any())
    <ul class="errors">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
@endif

<div class="panel">
    <div class="table-wrap">
        <table class="tbl">
            <thead>
                <tr><th>Pencari</th><th>Keahlian yang Diajukan</th><th>Surat Rekomendasi</th><th>Status</th><th style="min-width:280px">Keputusan Admin</th></tr>
            </thead>
            <tbody>
            @forelse($data as $item)
                <tr>
                    <td>{{ $item->pencariKerja->nama ?? '-' }}</td>
                    <td>
                        <strong>{{ $item->judul_keahlian ?: '-' }}</strong>
                        <div class="muted">{{ $item->deskripsi_keahlian ?: '' }}</div>
                        <div class="muted" style="font-size:.78rem">Diajukan: {{ $item->tanggal_upload ?: '-' }}</div>
                    </td>
                    <td>
                        @if($item->file_surat_rekomendasi)
                            <a href="{{ route('dokumen.keahlian', $item) }}" target="_blank" rel="noopener" class="btn btn-sm btn-ghost">Lihat File</a>
                        @else
                            <span class="muted">Tidak ada file</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $item->status_verifikasi_keahlian }}">{{ ucfirst($item->status_verifikasi_keahlian) }}</span>
                        <div class="muted" style="font-size:.78rem;margin-top:.3rem">Kategori: {{ $item->keahlian->nama_keahlian ?? 'belum ditentukan' }}</div>
                    </td>
                    <td>
                        <form action="{{ route('admin.verifikasi-keahlian.keputusan', $item->id_keahlian_pencari) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="kembali_ke" value="{{ $filter }}">
                            <div class="field" style="margin-bottom:.5rem">
                                <select name="id_keahlian" aria-label="Kategori keahlian">
                                    <option value="">-- Pilih kategori --</option>
                                    @foreach($keahlian as $k)
                                        <option value="{{ $k->id_keahlian }}" @selected((int) $item->id_keahlian === (int) $k->id_keahlian)>{{ $k->nama_keahlian }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field" style="margin-bottom:.6rem">
                                <input type="text" name="kategori_baru" maxlength="100" placeholder="atau ketik kategori baru" aria-label="Kategori baru">
                            </div>
                            <div class="actions">
                                <button type="submit" name="status_verifikasi_keahlian" value="terverifikasi" class="btn btn-sm btn-ok">Verifikasi</button>
                                <button type="submit" name="status_verifikasi_keahlian" value="ditolak" class="btn btn-sm btn-danger" onclick="return confirm('Tolak pengajuan keahlian ini?')">Tolak</button>
                                @if($item->status_verifikasi_keahlian !== 'menunggu')
                                    <button type="submit" name="status_verifikasi_keahlian" value="menunggu" class="btn btn-sm btn-ghost">Kembalikan ke menunggu</button>
                                @endif
                            </div>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty-row">Tidak ada pengajuan keahlian{{ $filter !== 'semua' ? ' dengan status ' . $filter : '' }}.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
