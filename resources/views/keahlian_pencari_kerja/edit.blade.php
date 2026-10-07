<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Keahlian</title>
</head>

<body>

<div style="max-width:700px;margin:40px auto;">

    <h2>Edit Pengajuan Keahlian</h2>

    <p>
        Status saat ini:
        <strong>
            {{ ucfirst($row->status_verifikasi_keahlian) }}
        </strong>
    </p>

    @if($errors->any())

        <div style="background:#f8d7da;padding:15px;">

            <ul>

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        action="{{ route(
            'keahlian_pencari_kerja.update',
            $row->id_keahlian_pencari
        ) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        <div style="margin-bottom:15px;">

            <label>
                Judul Keahlian
            </label>

            <input
                type="text"
                name="judul_keahlian"
                value="{{ old(
                    'judul_keahlian',
                    $row->judul_keahlian
                ) }}"
                required
                style="width:100%;padding:10px;"
            >

        </div>

        <div style="margin-bottom:15px;">

            <label>
                Deskripsi Keahlian
            </label>

            <textarea
                name="deskripsi_keahlian"
                required
                style="width:100%;height:120px;padding:10px;"
            >{{ old(
                'deskripsi_keahlian',
                $row->deskripsi_keahlian
            ) }}</textarea>

        </div>

        <div style="margin-bottom:15px;">

            <label>
                Ganti Surat Rekomendasi
            </label>

            <input
                type="file"
                name="file_surat_rekomendasi"
                accept=".jpg,.jpeg,.png,.pdf"
            >

            @if($row->file_surat_rekomendasi)

                <p>
                    File sebelumnya: <a href="{{ route('dokumen.keahlian', $row) }}">Lihat dokumen</a>
                </p>

            @endif

        </div>

        <button type="submit">
            Simpan Perubahan
        </button>

        <a
            href="{{ route('keahlian_pencari_kerja.index') }}"
        >
            Batal
        </a>

    </form>

</div>

</body>
</html>