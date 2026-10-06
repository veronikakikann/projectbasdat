<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Ajukan Keahlian</title>
</head>
<body>
    <h1>Ajukan Keahlian</h1>

    <a href="{{ route('keahlian_pencari_kerja.index') }}">
        ← Kembali ke Keahlian Saya
    </a>

    <hr>

    @if(session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    @if(session('error'))
        <p style="color: red;">
            {{ session('error') }}
        </p>
    @endif

    @if($errors->any())
        <div style="color: red;">
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

        <label>Judul Keahlian:</label><br>

        <input
            type="text"
            name="judul_keahlian"
            value="{{ old('judul_keahlian') }}"
            placeholder="Contoh: Bisa memperbaiki AC"
            required
        >

        <br><br>

        <label>Deskripsi Keahlian:</label><br>

        <textarea
            name="deskripsi_keahlian"
            rows="5"
            cols="50"
            placeholder="Jelaskan kemampuan atau pengalaman yang kamu miliki..."
            required
        >{{ old('deskripsi_keahlian') }}</textarea>

        <br><br>

        <label>Bukti / Surat Rekomendasi:</label><br>

        <input
            type="file"
            name="file_surat_rekomendasi"
            accept=".jpg,.jpeg,.png,.pdf"
            required
        >

        <br>

        <small>
            Format: JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.
        </small>

        <br><br>

        <button type="submit">
            Ajukan Keahlian
        </button>

    </form>
</body>
</html>