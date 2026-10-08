@extends('pemberi.layout')

@section('title', 'Beri Rating')

@section('content')

<style>
    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--color-ink);
        margin-bottom: 0.4rem;
    }

    .page-desc {
        color: var(--color-ink-soft);
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .form-card {
        max-width: 720px;
        background: white;
        border: 1px solid var(--color-border);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        padding: 1.5rem;
    }

    .job-summary {
        background: #F7FBFF;
        border: 1px solid #DCEEF9;
        border-radius: 10px;
        padding: 1.2rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .summary-label {
        color: #55B4EA;
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.4rem;
    }

    .summary-job {
        color: var(--color-ink);
        font-family: 'Manrope', sans-serif;
        font-size: 1.05rem;
        font-weight: 800;
        margin-bottom: 0.3rem;
    }

    .summary-worker {
        color: var(--color-ink-soft);
        font-size: 0.85rem;
    }

    .alert-error {
        background: #FED7D7;
        border: 1px solid #EF4444;
        color: #C53030;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
    }

    .error-list {
        margin: 0.5rem 0 0;
        padding-left: 1.2rem;
        line-height: 1.6;
    }

    .form-group {
        margin-bottom: 1.3rem;
    }

    .form-label {
        display: block;
        color: var(--color-ink);
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .required {
        color: #EF4444;
    }

    .form-select,
    .form-input {
        width: 100%;
        box-sizing: border-box;
        padding: 0.75rem 0.85rem;
        background: white;
        color: var(--color-ink);
        border: 1px solid var(--color-border);
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.9rem;
        transition: 0.2s;
    }

    .form-select:focus,
    .form-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(85, 180, 234, 0.12);
    }

    .form-help {
        margin-top: 0.45rem;
        color: var(--color-ink-soft);
        font-size: 0.78rem;
        line-height: 1.5;
    }

    .rating-options {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.65rem;
    }

    .rating-option {
        position: relative;
    }

    .rating-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .rating-option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
        min-height: 76px;
        background: white;
        border: 1px solid var(--color-border);
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s;
    }

    .rating-option label:hover {
        border-color: var(--color-primary);
        background: #F8FCFF;
    }

    .rating-option input:checked + label {
        border-color: #F59E0B;
        background: #FFF7ED;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.12);
    }

    .rating-star {
        font-size: 1.3rem;
        line-height: 1;
    }

    .rating-number {
        color: var(--color-ink);
        font-size: 0.78rem;
        font-weight: 800;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.7rem;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--color-border);
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.65rem 1rem;
        background: white;
        border: 1px solid var(--color-border);
        color: var(--color-ink);
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .btn-back:hover {
        background: #F8FAFC;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.65rem 1rem;
        background: var(--color-primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-submit:hover {
        background: var(--color-primary-dark);
    }

    @media (max-width: 600px) {
        .rating-options {
            grid-template-columns: repeat(5, 1fr);
            gap: 0.4rem;
        }

        .rating-option label {
            min-height: 65px;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .btn-back,
        .btn-submit {
            width: 100%;
        }
    }
</style>

<div class="page-header">

```
<h1 class="page-title">
    Beri Rating
</h1>

<p class="page-desc">
    Berikan penilaian untuk pekerja setelah pekerjaan selesai.
</p>
```

</div>

@if($errors->any())

```
<div class="alert-error">

    <strong>Terdapat kesalahan:</strong>

    <ul class="error-list">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

</div>
```

@endif

<div class="form-card">

```
<div class="job-summary">

    <div class="summary-label">
        Transaksi Selesai
    </div>

    <div class="summary-job">
        {{ $lamaran->pekerjaan->nama_pekerjaan ?? 'Pekerjaan' }}
    </div>

    <div class="summary-worker">
        Pekerja:
        {{ $lamaran->pencariKerja->nama ?? 'Pekerja' }}
    </div>

</div>

<form
    action="{{ route('pemberi.rating.store', $lamaran->id_lamaran) }}"
    method="POST"
>

    @csrf

    <div class="form-group">

        <label class="form-label">
            Penilaian <span class="required">*</span>
        </label>

        <div class="rating-options">

            @for($i = 1; $i <= 5; $i++)

                <div class="rating-option">

                    <input
                        type="radio"
                        id="skor-{{ $i }}"
                        name="skor"
                        value="{{ $i }}"
                        {{ (string) old('skor', $rating->skor ?? '') === (string) $i ? 'checked' : '' }}
                        required
                    >

                    <label for="skor-{{ $i }}">

                        <span class="rating-star">
                            ⭐
                        </span>

                        <span class="rating-number">
                            {{ $i }} / 5
                        </span>

                    </label>

                </div>

            @endfor

        </div>

        <div class="form-help">
            Pilih nilai 1 sampai 5 sesuai dengan hasil pekerjaan.
        </div>

    </div>

    <div class="form-group">

        <label
            for="kategori_komentar"
            class="form-label"
        >
            Komentar
        </label>

        <input
            type="text"
            id="kategori_komentar"
            name="kategori_komentar"
            class="form-input"
            maxlength="100"
            value="{{ old('kategori_komentar', $rating->kategori_komentar ?? '') }}"
            placeholder="Contoh: Pekerjaan rapi dan selesai tepat waktu"
        >

        <div class="form-help">
            Maksimal 100 karakter.
        </div>

    </div>

    <div class="form-actions">

        <a
            href="{{ route('pemberi.pekerjaan.show', $lamaran->id_pekerjaan) }}"
            class="btn-back"
        >
            Kembali
        </a>

        <button
            type="submit"
            class="btn-submit"
            onclick="return confirm('Yakin ingin menyimpan rating ini?')"
        >
            ⭐ Simpan Rating
        </button>

    </div>

</form>
```

</div>

@endsection
