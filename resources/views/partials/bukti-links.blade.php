@if($bukti?->foto_bukti_kerja)
    <a href="{{ route('dokumen.bukti', [$bukti, 'kerja']) }}" target="_blank" rel="noopener">Lihat Bukti Kerja</a>
@endif
@if($bukti?->foto_bukti_bayar)
    <a href="{{ route('dokumen.bukti', [$bukti, 'bayar']) }}" target="_blank" rel="noopener">Lihat Bukti Pembayaran</a>
@endif
