<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profil Pencari Kerja</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
        }

        .navbar {
            background: #1f3c88;
            color: white;
            padding: 16px 30px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        textarea {
            min-height: 100px;
        }

        input[readonly] {
            background: #eee;
        }

        button {
            background: #1f3c88;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<div class="navbar">

    <a href="{{ route('pencari.dashboard') }}">
        Dashboard
    </a>

    <a href="{{ route('pencari.cari-pekerjaan') }}">
        Cari Pekerjaan
    </a>

    <a href="{{ route('pencari.lamaran-saya') }}">
        Lamaran Saya
    </a>

</div>

<div class="container">

    <div class="card">

        <h2>Profil Pencari Kerja</h2>

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
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

        <form action="{{ route('pencari.profil.update') }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="form-group">

                <label>NIK</label>

                <input
                    type="text"
                    value="{{ $pencari->nik }}"
                    readonly
                >

                <small>
                    NIK tidak dapat diubah karena digunakan sebagai
                    identitas dan proses verifikasi.
                </small>

            </div>

            <div class="form-group">

                <label for="nama">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama', $pencari->nama) }}"
                    required
                >

            </div>

            <div class="form-group">

                <label for="alamat">
                    Alamat
                </label>

                <textarea
                    id="alamat"
                    name="alamat"
                    required
                >{{ old('alamat', $pencari->alamat) }}</textarea>

            </div>

            <div class="form-group">

                <label for="no_telpon">
                    Nomor Telepon
                </label>

                <input
                    type="text"
                    id="no_telpon"
                    name="no_telpon"
                    value="{{ old('no_telpon', $pencari->no_telpon) }}"
                    required
                >

            </div>

            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $pencari->email) }}"
                    required
                >

            </div>

            <div class="form-group">

                <label for="latitude">
                    Latitude
                </label>

                <input
                    type="number"
                    step="any"
                    id="latitude"
                    name="latitude"
                    value="{{ old('latitude', $pencari->latitude) }}"
                >

            </div>

            <div class="form-group">

                <label for="longitude">
                    Longitude
                </label>

                <input
                    type="number"
                    step="any"
                    id="longitude"
                    name="longitude"
                    value="{{ old('longitude', $pencari->longitude) }}"
                >

            </div>

            <div class="form-group">
                <label for="password">Password baru (opsional)</label>
                <input type="password" id="password" name="password" minlength="6" autocomplete="new-password">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
            </div>

            <button type="submit">
                Simpan Perubahan
            </button>

        </form>

    </div>

</div>

</body>
</html>