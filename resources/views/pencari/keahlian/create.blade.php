@extends('pencari.layout')

@section('title', 'Ajukan Keahlian')

@section('content')

<div style="background:white; border:1px solid var(--color-border); border-radius:12px; padding:2rem; max-width:850px;">

    <h2 style="font-family:'Manrope',sans-serif; font-size:1.5rem; font-weight:800; margin-bottom:.5rem;">
        Ajukan Keahlian
    </h2>

    <p style="color:var(--color-ink-soft); margin-bottom:2rem;">
        Masukkan keahlian yang kamu miliki. Kategori keahlian akan ditentukan oleh Admin setelah proses verifikasi.
    </p>

    <form
        action="{{ route('keahlian_pencari_kerja.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <div style="margin-bottom:1.5rem;">

            <label style="display:block; font-weight:600; margin-bottom:.5rem;">
                Judul Keahlian
            </label>

            <input
                type="text"
                name="judul_keahlian"
                value="{{ old('judul_keahlian') }}"
                placeholder="Contoh: Memperbaiki motor"
                required
                style="width:100%; padding:.75rem 1rem; border:1px solid var(--color-border); border-radius:8px;"
            >

            @error('judul_keahlian')
                <div style="color:#DC2626; font-size:.85rem; margin-top:.4rem;">
                    ⚠️ {{ $message }}
                </div>
            @enderror

        </div>

        <div style="margin-bottom:1.5rem;">

            <label style="display:block; font-weight:600; margin-bottom:.5rem;">
                Deskripsi Keahlian
            </label>

            <textarea
                name="deskripsi_keahlian"
                rows="6"
                placeholder="Jelaskan kemampuan atau pengalaman yang berkaitan dengan keahlian tersebut."
                required
                style="width:100%; padding:.75rem 1rem; border:1px solid var(--color-border); border-radius:8px;"
            >{{ old('deskripsi_keahlian') }}</textarea>

            @error('deskripsi_keahlian')
                <div style="color:#DC2626; font-size:.85rem; margin-top:.4rem;">
                    ⚠️ {{ $message }}
                </div>
            @enderror

        </div>

        <div style="margin-bottom:2rem;">

            <label style="display:block; font-weight:600; margin-bottom:.5rem;">
                Surat Rekomendasi / Bukti
            </label>

            <input
                type="file"
                name="file_surat_rekomendasi"
                accept=".jpg,.jpeg,.png,.pdf"
                required
                style="width:100%; padding:.65rem; border:1px solid var(--color-border); border-radius:8px;"
            >

            <div style="font-size:.8rem; color:var(--color-ink-soft); margin-top:.4rem;">
                Format: JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.
            </div>

            @error('file_surat_rekomendasi')
                <div style="color:#DC2626; font-size:.85rem; margin-top:.4rem;">
                    ⚠️ {{ $message }}
                </div>
            @enderror

        </div>

        <div style="display:flex; gap:.75rem;">

            <button
                type="submit"
                style="background:var(--color-primary); color:white; border:none; padding:.75rem 1.25rem; border-radius:8px; font-weight:700; cursor:pointer;"
            >
                Ajukan Keahlian
            </button>

            <a
                href="{{ route('keahlian_pencari_kerja.index') }}"
                style="padding:.75rem 1.25rem; border:1px solid var(--color-border); border-radius:8px; text-decoration:none; color:var(--color-ink); font-weight:600;"
            >
                Batal
            </a>

        </div>

    </form>

</div>

@endsection