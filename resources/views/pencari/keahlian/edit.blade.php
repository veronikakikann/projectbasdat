@extends('pencari.layout')

@section('title', 'Edit Pengajuan Keahlian')

@section('content')

<div style="background:white; border:1px solid var(--color-border); border-radius:12px; padding:2rem; max-width:850px;">

    <h2 style="font-family:'Manrope',sans-serif; font-size:1.5rem; font-weight:800; margin-bottom:.5rem;">
        Edit Pengajuan Keahlian
    </h2>

    <p style="color:var(--color-ink-soft); margin-bottom:2rem;">
        Perbaiki data pengajuanmu. Setelah diedit, pengajuan akan kembali berstatus menunggu verifikasi.
    </p>

    <form
        action="{{ route('keahlian_pencari_kerja.update', $row->id_keahlian_pencari) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div style="margin-bottom:1.5rem;">

            <label style="display:block; font-weight:600; margin-bottom:.5rem;">
                Judul Keahlian
            </label>

            <input
                type="text"
                name="judul_keahlian"
                value="{{ old('judul_keahlian', $row->judul_keahlian) }}"
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
                required
                style="width:100%; padding:.75rem 1rem; border:1px solid var(--color-border); border-radius:8px;"
            >{{ old('deskripsi_keahlian', $row->deskripsi_keahlian) }}</textarea>

            @error('deskripsi_keahlian')
                <div style="color:#DC2626; font-size:.85rem; margin-top:.4rem;">
                    ⚠️ {{ $message }}
                </div>
            @enderror

        </div>

        <div style="margin-bottom:2rem;">

            <label style="display:block; font-weight:600; margin-bottom:.5rem;">
                Surat Rekomendasi / Bukti Baru
            </label>

            <input
                type="file"
                name="file_surat_rekomendasi"
                accept=".jpg,.jpeg,.png,.pdf"
                style="width:100%; padding:.65rem; border:1px solid var(--color-border); border-radius:8px;"
            >

            <div style="font-size:.8rem; color:var(--color-ink-soft); margin-top:.4rem;">
                Kosongkan jika ingin mempertahankan file lama.
                Format JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.
            </div>

            @if($row->file_surat_rekomendasi)

                <div style="margin-top:.75rem;">
                    <a
                        href="{{ route('dokumen.keahlian', $row) }}"
                        target="_blank"
                    >
                        Lihat file yang sekarang
                    </a>
                </div>

            @endif

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
                Simpan Perubahan
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