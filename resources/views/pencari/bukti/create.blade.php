@extends('pencari.layout')

@section('title', 'Upload Bukti Kerja')

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
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .alert-error {
        background: #FED7D7;
        border: 1px solid #EF4444;
        color: #C53030;
    }

    .form-card {
        background: white;
        border: 1px solid var(--color-border);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        max-width: 760px;
    }

    .job-summary {
        background: #F7FBFF;
        border: 1px solid #DCEEF9;
        border-radius: 10px;
        padding: 1.2rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .summary-title {
        color: #55B4EA;
        font-size: 0.78rem;
        font-weight: 800;
        margin-bottom: 0.45rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .summary-job {
        color: var(--color-ink);
        font-family: 'Manrope', sans-serif;
        font-size: 1.05rem;
        font-weight: 800;
        margin-bottom: 0.35rem;
    }

    .summary-employer {
        color: var(--color-ink-soft);
        font-size: 0.85rem;
        margin-bottom: 0.85rem;
    }

    .summary-status {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        background: #D1FAE5;
        color: #047857;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .form-intro {
        color: var(--color-ink-soft);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
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

    .file-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--color-border);
        border-radius: 8px;
        background: white;
        color: var(--color-ink);
        font-size: 0.85rem;
        box-sizing: border-box;
    }

    .file-input:focus,
    .textarea-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(85, 180, 234, 0.12);
    }

    .file-help {
        margin-top: 0.5rem;
        color: var(--color-ink-soft);
        font-size: 0.78rem;
        line-height: 1.5;
    }

    .textarea-input {
        width: 100%;
        min-height: 130px;
        padding: 0.8rem 0.9rem;
        border: 1px solid var(--color-border);
        border-radius: 8px;
        background: white;
        color: var(--color-ink);
        font-family: inherit;
        font-size: 0.9rem;
        line-height: 1.5;
        resize: vertical;
        box-sizing: border-box;
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
        border: 1px solid var(--color-border);
        background: white;
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
        border: none;
        background: var(--color-primary);
        color: white;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-submit:hover {
        background: var(--color-primary-dark);
    }

    .error-list {
        margin: 0.5rem 0 0;
        padding-left: 1.2rem;
        font-size: 0.82rem;
        line-height: 1.6;
        font-weight: 500;
    }

    @media (max-width: 600px) {
        .form-card {
            padding: 1.1rem;
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
    <h1 class="page-title">
        Upload Bukti Kerja
    </h1>

```
<p class="page-desc">
    Kirim bukti pengerjaan kepada pemberi kerja setelah pekerjaan selesai dikerjakan.
</p>
```

</div>

@if($errors->any()) <div class="alert alert-error"> <strong>Terdapat kesalahan:</strong>

```
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
    <div class="summary-title">
        Pekerjaan
    </div>

    <div class="summary-job">
        {{ $lamaran->pekerjaan->nama_pekerjaan ?? '-' }}
    </div>

    <div class="summary-employer">
        {{ $lamaran->pekerjaan->pemberiKerja->nama ?? 'Pemberi Kerja' }}
    </div>

    <span class="summary-status">
        ✓ Lamaran Diterima
    </span>
</div>

<div class="form-intro">
    Silakan unggah bukti bahwa pekerjaan telah selesai dikerjakan.
    Bukti akan diperiksa oleh pemberi kerja sebelum pembayaran dilakukan.
</div>

<form
    action="{{ route('pencari.bukti.store', $lamaran->id_lamaran) }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf

    <div class="form-group">
        <label
            for="foto_bukti_kerja"
            class="form-label"
        >
            Bukti Pekerjaan <span class="required">*</span>
        </label>

        <input
            type="file"
            id="foto_bukti_kerja"
            name="foto_bukti_kerja"
            class="file-input"
            accept=".jpg,.jpeg,.png,.pdf"
            required
        >

        <div class="file-help">
            Format yang diperbolehkan: JPG, JPEG, PNG, atau PDF.
            Ukuran maksimal 2 MB.
        </div>
    </div>

    <div class="form-group">
        <label
            for="catatan"
            class="form-label"
        >
            Catatan
        </label>

        <textarea
            id="catatan"
            name="catatan"
            class="textarea-input"
            maxlength="1000"
            placeholder="Tambahkan catatan mengenai pekerjaan jika diperlukan..."
        >{{ old('catatan') }}</textarea>

        <div class="file-help">
            Maksimal 1.000 karakter.
        </div>
    </div>

    <div class="form-actions">

        <a
            href="{{ route('pencari.lamaran-saya') }}"
            class="btn-back"
        >
            Kembali
        </a>

        <button
            type="submit"
            class="btn-submit"
            onclick="return confirm('Yakin bukti pekerjaan sudah benar dan ingin dikirim?')"
        >
            📤 Kirim Bukti Pekerjaan
        </button>

    </div>
</form>
```

</div>

@endsection
