@extends('pemberi.layout')

@section('title', $rating ? 'Edit Rating' : 'Beri Rating')

@section('content')
<style>
    .form-card {
        background: white; border-radius: 12px; border: 1px solid var(--color-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02); max-width: 600px; padding: 2.5rem; margin: 0 auto;
    }
    .page-title { font-family: 'Manrope', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--color-ink); margin-bottom: 0.5rem; text-align: center; }
    .page-desc { color: var(--color-ink-soft); font-size: 0.9rem; margin-bottom: 2rem; text-align: center; }

    /* Profil Singkat Pekerja */
    .worker-profile { display: flex; align-items: center; justify-content: center; gap: 1rem; padding: 1rem; background: #F8FAFC; border-radius: 8px; margin-bottom: 2rem; border: 1px solid var(--color-border); }
    .app-avatar { width: 48px; height: 48px; border-radius: 50%; background: var(--color-primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem; }
    
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.95rem; font-weight: 700; color: var(--color-ink); margin-bottom: 0.75rem; text-align: center; }
    
    /* Interactive Stars */
    .star-rating-container { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
    .stars-group { display: flex; gap: 8px; flex-direction: row-reverse; justify-content: center; }
    
    .star-input { display: none; }
    .star-label {
        cursor: pointer;
        color: #E2E8F0;
        transition: color 0.2s, transform 0.1s;
    }
    .star-label svg { width: 40px; height: 40px; fill: currentColor; }
    
    /* Logika hover & checked dari kanan ke kiri (flex-direction: row-reverse) */
    .star-label:hover,
    .star-label:hover ~ .star-label,
    .star-input:checked ~ .star-label {
        color: #F59E0B;
    }
    .star-label:active { transform: scale(0.9); }

    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 0.95rem; color: var(--color-ink); transition: 0.2s; }
    .form-control:focus { outline: none; border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(85, 180, 234, 0.1); }
    .form-control.is-invalid { border-color: #EF4444; background-color: #FEF2F2; }
    
    .invalid-feedback { color: #DC2626; font-size: 0.8rem; font-weight: 500; margin-top: 0.4rem; text-align: center; }
    
    .btn-submit { background-color: var(--color-primary); color: white; padding: 0.8rem 2rem; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; width: 100%; font-size: 1rem; }
    .btn-submit:hover { background-color: var(--color-primary-dark); }
</style>

<div class="form-card">
    <h2 class="page-title">{{ $rating ? 'Edit Ulasan Anda' : 'Beri Ulasan Pekerja' }}</h2>
    <p class="page-desc">Nilai kinerja pekerja untuk membantu pemberi kerja lainnya.</p>

    <!-- Info Pekerja -->
    <div class="worker-profile">
        <div class="app-avatar">{{ substr($lamaran->pencariKerja->nama ?? 'P', 0, 1) }}</div>
        <div>
            <div style="font-weight: 700; font-size: 1.1rem; color: var(--color-ink);">{{ $lamaran->pencariKerja->nama ?? 'Nama Pekerja' }}</div>
            <div style="color: var(--color-ink-soft); font-size: 0.85rem;">Mengerjakan: {{ $lamaran->pekerjaan->nama_pekerjaan ?? '-' }}</div>
        </div>
    </div>

    @if ($errors->any())
        <div style="background: #FEF2F2; border: 1px solid #EF4444; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: center; color: #B91C1C; font-size: 0.9rem; font-weight: 600;">
            Gagal menyimpan ulasan. Pastikan Anda memilih bintang dan mengisi kolom dengan benar.
        </div>
    @endif

    <!-- Form Dinamis: Cek apakah akan Update atau Store -->
    <form action="{{ $rating ? route('pemberi.rating.update', $rating->id_rating) : route('pemberi.rating.store', $lamaran->id_lamaran) }}" method="POST">
        @csrf
        @if($rating) 
            @method('PUT') 
        @endif

        <!-- Bintang Rating -->
        <div class="form-group">
            <label class="form-label">Berapa skor untuk pekerja ini?</label>
            <div class="star-rating-container">
                <div class="stars-group">
                    @php $currentSkor = old('skor', $rating->skor ?? 0); @endphp
                    
                    <!-- Bintang 5 -->
                    <input type="radio" name="skor" id="star5" value="5" class="star-input" {{ $currentSkor == 5 ? 'checked' : '' }}>
                    <label for="star5" class="star-label"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></label>
                    
                    <!-- Bintang 4 -->
                    <input type="radio" name="skor" id="star4" value="4" class="star-input" {{ $currentSkor == 4 ? 'checked' : '' }}>
                    <label for="star4" class="star-label"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></label>
                    
                    <!-- Bintang 3 -->
                    <input type="radio" name="skor" id="star3" value="3" class="star-input" {{ $currentSkor == 3 ? 'checked' : '' }}>
                    <label for="star3" class="star-label"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></label>
                    
                    <!-- Bintang 2 -->
                    <input type="radio" name="skor" id="star2" value="2" class="star-input" {{ $currentSkor == 2 ? 'checked' : '' }}>
                    <label for="star2" class="star-label"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></label>
                    
                    <!-- Bintang 1 -->
                    <input type="radio" name="skor" id="star1" value="1" class="star-input" {{ $currentSkor == 1 ? 'checked' : '' }}>
                    <label for="star1" class="star-label"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></label>
                </div>
                @error('skor') <div class="invalid-feedback">Anda belum memberikan rating bintang.</div> @enderror
            </div>
        </div>

        <div class="form-group" style="margin-top: 2rem;">
            <label class="form-label" style="text-align: left;">Komentar Singkat (Opsional)</label>
            <textarea name="kategori_komentar" class="form-control @error('kategori_komentar') is-invalid @enderror" placeholder="Contoh: Sangat cekatan dan sopan" style="min-height: 100px; resize: vertical;" maxlength="100">{{ old('kategori_komentar', $rating->kategori_komentar ?? '') }}</textarea>
            <div style="font-size: 0.75rem; color: var(--color-ink-soft); margin-top: 4px; text-align: right;">Maksimal 100 karakter</div>
            @error('kategori_komentar') <div class="invalid-feedback" style="text-align: left;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn-submit">Kirim Ulasan</button>
            <a href="{{ route('pemberi.pekerjaan.show', $lamaran->id_pekerjaan) }}" style="display: block; text-align: center; margin-top: 1rem; color: var(--color-ink-soft); font-weight: 600; font-size: 0.9rem; text-decoration: none;">Batal & Kembali</a>
        </div>
    </form>
</div>
@endsection