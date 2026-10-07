<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Data {{ ucfirst($jenis) }}</title></head>
<body>
<h1>Data {{ ucfirst($jenis) }}</h1>
<a href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a>
<p>Riwayat transaksi ditampilkan untuk pemantauan. Keputusan lamaran, bukti, dan rating dikelola pihak yang terlibat.</p>
<table border="1" cellpadding="8">
<thead><tr><th>ID</th>@foreach($columns as $column)<th>{{ ucwords(str_replace('_', ' ', $column)) }}</th>@endforeach @if($jenis === 'bukti')<th>Dokumen</th>@endif</tr></thead>
<tbody>
@forelse($records as $record)
<tr><td>{{ $record->getKey() }}</td>
@foreach($columns as $column)<td>{{ $record->$column ?? '-' }}</td>@endforeach
@if($jenis === 'bukti')
<td>
@if($record->foto_bukti_kerja)<a href="{{ route('dokumen.bukti', [$record, 'kerja']) }}">Bukti kerja</a>@endif
@if($record->foto_bukti_bayar)<a href="{{ route('dokumen.bukti', [$record, 'bayar']) }}">Bukti bayar</a>@endif
</td>
@endif
</tr>
@empty<tr><td colspan="{{ count($columns) + 2 }}">Belum ada data.</td></tr>@endforelse
</tbody>
</table>
{{ $records->links() }}
</body></html>
