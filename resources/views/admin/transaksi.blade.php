@extends('admin.layout')
@section('title', 'Data ' . ucfirst($jenis))
@section('content')
<div class="panel">
    <p class="muted" style="margin-bottom:1rem">Riwayat transaksi ditampilkan untuk pemantauan. Keputusan lamaran, bukti, dan rating dikelola pihak yang terlibat.</p>
    <div class="table-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th>ID</th>
                    @foreach($columns as $column)<th>{{ ucwords(str_replace('_', ' ', $column)) }}</th>@endforeach
                    @if($jenis === 'bukti')<th>Dokumen</th>@endif
                </tr>
            </thead>
            <tbody>
            @forelse($records as $record)
                <tr>
                    <td>{{ $record->getKey() }}</td>
                    @foreach($columns as $column)<td>{{ $record->$column ?? '-' }}</td>@endforeach
                    @if($jenis === 'bukti')
                        <td>
                            <div class="actions">
                                @if($record->foto_bukti_kerja)<a href="{{ route('dokumen.bukti', [$record, 'kerja']) }}" target="_blank" rel="noopener" class="btn btn-sm btn-ghost">Bukti kerja</a>@endif
                                @if($record->foto_bukti_bayar)<a href="{{ route('dokumen.bukti', [$record, 'bayar']) }}" target="_blank" rel="noopener" class="btn btn-sm btn-ghost">Bukti bayar</a>@endif
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="{{ count($columns) + ($jenis === 'bukti' ? 2 : 1) }}" class="empty-row">Belum ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($records->hasPages())
        <div class="pager">
            <span>Halaman {{ $records->currentPage() }} dari {{ $records->lastPage() }}</span>
            @if($records->previousPageUrl())<a href="{{ $records->previousPageUrl() }}" class="btn btn-sm btn-ghost">&laquo; Sebelumnya</a>@endif
            @if($records->nextPageUrl())<a href="{{ $records->nextPageUrl() }}" class="btn btn-sm btn-ghost">Berikutnya &raquo;</a>@endif
        </div>
    @endif
</div>
@endsection
