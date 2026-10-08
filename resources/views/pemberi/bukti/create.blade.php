@extends('pemberi.layout')

@section('title', 'Penyelesaian & Pembayaran')

@section('content')
<style>
    .page-title { font-family: 'Manrope', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--color-ink); margin-bottom: 0.25rem; }
    .page-desc { color: var(--color-ink-soft); font-size: 0.95rem; margin-bottom: 2rem; }
    
    .worker-card {
        background: white; border-radius: 12px; border: 1px solid var(--color-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02); margin-bottom: 1.5rem; overflow: hidden;
    }
    
    .worker-header {
        background: #F8FAFC; padding: 1rem 1.5rem; border-bottom: 1px solid var(--color-border);
        display: flex; align-items: center; justify-content: space-between;
    }
    
    .worker-profile { display: flex; align-items: center; gap: 1rem; }
    .worker-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--color-primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem; }
    
    .grid-content { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; padding: 1.5rem; }
    
    .section-title { font-size: 0.9rem; font-weight: 700; color: var(--color-ink); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .status-box { padding: 1rem; border-radius: 8px; border: 1px solid var(--color-border); display: flex; align-items: flex-start; gap: 1rem; }
    .status-icon { width: 24px; height: 24px; flex-shrink: 0; }
    .status-text { font-size: 0.9rem; color: var(--color-ink-soft); line-height: 1.5; }
    .status-text strong { color: var(--color-ink); display: block; margin-bottom: 0.25rem; font-size: 0.95rem; }
    
    .form-group { margin-bottom: 1.25rem; }
    .form-label { display: block; font-size: 0.9rem; font-weight: 600; color: var(--color-ink); margin-bottom: 0.5rem; }
    .form-control { width: 100%; padding: 0.6rem 0.8rem; border: 1px solid var(--color-border); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 0.9rem; color: var(--color-ink); }
    .form-control:focus { outline: none; border-color: var(--color-primary); }
    .is-invalid { border-color: #EF4444; background: #FEF2F2; }
    .invalid-feedback { color: #DC2626; font-size: 0.8rem; font-weight: 500; margin-top: 0.4rem; }
    
    .btn-submit { background-color: var(--color-primary); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem; }
    .btn-submit:hover { background-color: var(--color-primary-dark); }
</style>

<div>
    <h2 class="page-title">Penyelesaian & Pembayaran</h2>
    <p class="page-desc">Pekerjaan: <strong style="color: var(--color-ink);">{{ $pekerjaan->nama_pekerjaan }}</strong></p>

    @if (session('error'))
        <div style="background: #FEF2F2; border: 1px solid #EF4444; color: #B91C1C; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">⚠️ {{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div style="background: #C6F6D5; border: 1px solid #48BB78; color: #2F855A; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">✅ {{ session('success') }}</div>
    @endif

    @foreach($lamarans as $l)
        @php
            $bukti = $l->buktiPenyelesaian;
            $sudahKerja = $bukti && $bukti->foto_bukti_kerja;
            $sudahSelesai = $l->status_lamaran === 'selesai';
        @endphp

        <div class="worker-card">
            <div class="worker-header">
                <div class="worker-profile">
                    <div class="worker-avatar">{{ substr($l->pencariKerja->nama ?? 'P', 0, 1) }}</div>
                    <div>
                        <div style="font-weight: 700; color: var(--color-ink);">{{ $l->pencariKerja->nama ?? 'Nama Pekerja' }}</div>
                        <div style="font-size: 0.8rem; color: var(--color-ink-soft);">Status: <span style="text-transform: capitalize; font-weight: 600;">{{ $l->status_lamaran }}</span></div>
                    </div>
                </div>
            </div>

            <div class="grid-content">
                <!-- Sisi Kiri: Status Kerja (Dari Pekerja) -->
                <div>
                    <h3 class="section-title">Langkah 1: Bukti Hasil Kerja</h3>
                    
                    @if($sudahKerja)
                        <div class="status-box" style="background: #F0FFF4; border-color: #9AE6B4;">
                            <svg class="status-icon" style="color: #38A169;" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <div class="status-text">
                                <strong>Pekerjaan Telah Diselesaikan</strong>
                                Pekerja ini telah mengunggah bukti hasil kerjanya. Anda dapat melanjutkan ke proses pembayaran.
                                @if($bukti->catatan_kerja)
                                    <div style="margin-top: 8px; padding: 8px; background: white; border-radius: 4px; font-style: italic; border: 1px dashed #CBD5E0;">"{{ $bukti->catatan_kerja }}"</div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="status-box" style="background: #FFFFAF; border-color: #F6E05E;">
                            <svg class="status-icon" style="color: #D69E2E;" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <div class="status-text">
                                <strong>Menunggu Bukti Kerja</strong>
                                Pekerja belum mengunggah bukti hasil kerja. Anda tidak dapat melakukan pembayaran sebelum pekerja menyelesaikan tugasnya di sistem.
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sisi Kanan: Status Pembayaran (Dari Anda) -->
                <div>
                    <h3 class="section-title">Langkah 2: Bukti Pembayaran</h3>
                    
                    @if($sudahSelesai)
                        <div class="status-box" style="background: #EBF8FF; border-color: #90CDF4;">
                            <svg class="status-icon" style="color: #3182CE;" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <div class="status-text">
                                <strong>Pembayaran Selesai</strong>
                                Transaksi dengan pekerja ini telah lunas dan selesai. Anda sudah dapat memberikan rating dan ulasan.
                            </div>
                        </div>
                    @elseif(!$sudahKerja)
                        <div style="padding: 1.5rem; text-align: center; color: var(--color-ink-soft); font-size: 0.9rem; border: 1px dashed var(--color-border); border-radius: 8px; background: #F8FAFC;">
                            Form pembayaran akan terbuka otomatis setelah pekerja mengunggah bukti hasil kerja pada Langkah 1.
                        </div>
                    @else
                        <!-- Form Upload Pembayaran (Hanya muncul jika pekerja sudah setor bukti & belum selesai) -->
                        <form action="{{ route('pemberi.bukti.store', $pekerjaan->id_pekerjaan) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_lamaran" value="{{ $l->id_lamaran }}">
                            
                            <div class="form-group">
                                <label class="form-label">Upload Bukti Transfer / Kwitansi</label>
                                <input type="file" name="foto_bukti_bayar" class="form-control @error('foto_bukti_bayar') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf" required>
                                <div style="font-size: 0.75rem; color: var(--color-ink-soft); margin-top: 4px;">Format: JPG, PNG, atau PDF. Maksimal 2MB.</div>
                                @if($errors->has('foto_bukti_bayar') && old('id_lamaran') == $l->id_lamaran)
                                    <div class="invalid-feedback">{{ $errors->first('foto_bukti_bayar') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="form-label">Catatan Pembayaran (Opsional)</label>
                                <textarea name="catatan_bayar" class="form-control" style="min-height: 60px; resize: vertical;" placeholder="Contoh: Lunas via BCA atas nama Budi"></textarea>
                            </div>

                            <button type="submit" class="btn-submit" onclick="return confirm('Apakah Anda yakin data pembayaran sudah benar? Pekerjaan ini akan ditandai selesai.');">
                                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                Konfirmasi & Selesaikan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    <div style="margin-top: 2rem; text-align: center;">
        <a href="{{ route('pemberi.pekerjaan.show', $pekerjaan->id_pekerjaan) }}" style="color: var(--color-ink-soft); font-weight: 600; text-decoration: none; font-size: 0.95rem;">← Kembali ke Detail Lowongan</a>
    </div>
</div>
@endsection