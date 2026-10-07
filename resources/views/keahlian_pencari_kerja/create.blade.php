<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ajukan Keahlian</title>
</head>

<body>

<div style="max-width:700px;margin:40px auto;">

    <h2>Ajukan Keahlian</h2>

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
        action="{{ route('keahlian_pencari_kerja.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        <div style="margin-bottom:15px;">

            <label>
                Judul Keahlian
            </label>

            <input
                type="text"
                name="judul_keahlian"
                value="{{ old('judul_keahlian') }}"
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
            >{{ old('deskripsi_keahlian') }}</textarea>

        </div>

        <div style="margin-bottom:15px;">

            <label>
                Surat Rekomendasi
            </label>

            <input
                type="file"
                name="file_surat_rekomendasi"
                accept=".jpg,.jpeg,.png,.pdf"
                required
            >

        </div>

        <button type="submit">
            Ajukan Keahlian
        </button>

        <a
            href="{{ route('keahlian_pencari_kerja.index') }}"
        >
            Kembali
        </a>

    </form>

</div>

</body>
</html>