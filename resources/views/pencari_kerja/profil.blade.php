<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profil Saya</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .header {
            background: #27ae60;
            color: white;
            padding: 25px 40px;
        }

        .header h1 {
            margin: 0 0 10px 0;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 30px auto;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .field input,
        .field textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .field input[readonly] {
            background: #f0f0f0;
        }

        .status {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 20px;
        }

        .menunggu {
            background: #fff3cd;
            color: #856404;
        }

        .terverifikasi {
            background: #d4edda;
            color: #155724;
        }

        .ditolak {
            background: #f8d7da;
            color: #721c24;
        }

        .btn {
            display: inline-block;
            background: #27ae60;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .back {
            color: white;
            text-decoration: none;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>

</head>

<body>

    <div class="header">

        <h1>Profil Saya</h1>

        <a
            href="{{ route('pencari.dashboard') }}"
            class="back"
        >
            ← Kembali ke Dashboard
        </a>

    </div>

    <div class="container">

        @if(session('success'))
            <p class="success">
                {{ session('success') }}
            </p>
        @endif

        @if(session('error'))
            <p class="error">
                {{ session('error') }}
            </p>
        @endif

        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">

            <h2>Data Pribadi</h2>

            <form
                action="{{ route('pencari.profil.update') }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                <div class="field">

                    <label>NIK</label>

                    <input
                        type="text"
                        value="{{ $pencari->nik }}"
                        maxlength="16"
                        readonly
                    >

                    <small style="color: #777;">
                        NIK tidak dapat diubah karena digunakan untuk verifikasi identitas.
                    </small>

        </div>

                <div class="field">

                    <label>Nama Lengkap</label>

                    <input
                        type="text"
                        name="nama"
                        value="{{ old('nama', $pencari->nama) }}"
                        required
                    >

                </div>

                <div class="field">

                    <label>Alamat</label>

                    <textarea
                        name="alamat"
                        rows="4"
                        required
                    >{{ old('alamat', $pencari->alamat) }}</textarea>

                </div>

                <div class="field">

                    <label>Nomor Telepon</label>

                    <input
                        type="text"
                        name="no_telpon"
                        value="{{ old('no_telpon', $pencari->no_telpon) }}"
                        required
                    >

                </div>

                <div class="field">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $pencari->email) }}"
                        required
                    >

                </div>

                <div class="field">

                    <label>Latitude</label>

                    <input
                        type="text"
                        name="latitude"
                        value="{{ old('latitude', $pencari->latitude) }}"
                        placeholder="Contoh: -7.28167"
                    >

                </div>

                <div class="field">

                    <label>Longitude</label>

                    <input
                        type="text"
                        name="longitude"
                        value="{{ old('longitude', $pencari->longitude) }}"
                        placeholder="Contoh: 112.7383"
                    >

                </div>

                <div class="field">

                    <label>Status Verifikasi Akun</label>

                    @if($pencari->status_verifikasi === 'terverifikasi')

                        <span class="status terverifikasi">
                            Terverifikasi
                        </span>

                    @elseif($pencari->status_verifikasi === 'ditolak')

                        <span class="status ditolak">
                            Ditolak
                        </span>

                    @else

                        <span class="status menunggu">
                            Menunggu Verifikasi
                        </span>

                    @endif

                </div>

                <button
                    type="submit"
                    class="btn"
                >
                    Simpan Perubahan
                </button>

            </form>

        </div>

    </div>

</body>

</html>