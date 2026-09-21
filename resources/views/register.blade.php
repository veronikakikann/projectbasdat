<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Daftar - Bursa Kerja Harian</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            min-height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;

            font-family: Arial, sans-serif;

            background: linear-gradient(
                135deg,
                #2c3e50,
                #3498db
            );

            padding: 40px 20px;

        }


        .register-box {

            width: 520px;

            background: white;

            padding: 40px;

            border-radius: 18px;

            box-shadow:
                0 15px 40px rgba(0, 0, 0, 0.2);

        }


        .title {

            text-align: center;

            margin-bottom: 30px;

        }


        .title h1 {

            margin: 0;

            color: #2c3e50;

            font-size: 30px;

        }


        .title p {

            color: #777;

            margin-top: 10px;

        }


        .form-group {

            margin-bottom: 18px;

        }


        label {

            display: block;

            margin-bottom: 8px;

            font-weight: bold;

            color: #333;

        }


        input,
        select,
        textarea {

            width: 100%;

            padding: 12px;

            border: 1px solid #ddd;

            border-radius: 9px;

            font-size: 15px;

        }


        textarea {

            min-height: 80px;

            resize: vertical;

        }


        input:focus,
        select:focus,
        textarea:focus {

            outline: none;

            border-color: #3498db;

        }


        .btn-register {

            width: 100%;

            padding: 14px;

            border: none;

            border-radius: 9px;

            background: #5b9bd5;

            color: white;

            font-size: 17px;

            font-weight: bold;

            cursor: pointer;

            margin-top: 5px;

        }


        .btn-register:hover {

            background: #4285c5;

        }


        .login-link {

            text-align: center;

            margin-top: 22px;

            padding-top: 20px;

            border-top: 1px solid #eee;

        }


        .login-link p {

            color: #777;

            margin: 0;

        }


        .login-link a {

            color: #3498db;

            font-weight: bold;

            text-decoration: none;

        }


        .login-link a:hover {

            text-decoration: underline;

        }


        .error {

            background: #f8d7da;

            color: #721c24;

            padding: 12px 15px;

            border-radius: 8px;

            margin-bottom: 20px;

        }

    </style>

</head>


<body>


<div class="register-box">


    <div class="title">

        <h1>BUAT AKUN</h1>

        <p>Daftar sebagai pengguna Bursa Kerja Harian</p>

    </div>


    {{-- Error --}}

    @if(session('error'))

        <div class="error">

            {{ session('error') }}

        </div>

    @endif


    {{-- Validation error --}}

    @if($errors->any())

        <div class="error">

            @foreach($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif


    <form
        action="{{ route('register.process') }}"
        method="POST"
    >

        @csrf


        <!-- ROLE -->

        <div class="form-group">

            <label for="role">
                Daftar sebagai
            </label>

            <select
                name="role"
                id="role"
                required
            >

                <option value="">
                    -- Pilih Role --
                </option>

                <option
                    value="pemberi_kerja"
                    {{ old('role') == 'pemberi_kerja' ? 'selected' : '' }}
                >
                    Pemberi Kerja
                </option>

                <option
                    value="pencari_kerja"
                    {{ old('role') == 'pencari_kerja' ? 'selected' : '' }}
                >
                    Pencari Kerja
                </option>

            </select>

        </div>


        <!-- NIK -->

        <div class="form-group">

            <label for="nik">
                NIK
            </label>

            <input
                type="text"
                name="nik"
                id="nik"
                placeholder="Masukkan NIK"
                value="{{ old('nik') }}"
                required
            >

        </div>


        <!-- NAMA -->

        <div class="form-group">

            <label for="nama">
                Nama Lengkap
            </label>

            <input
                type="text"
                name="nama"
                id="nama"
                placeholder="Masukkan nama lengkap"
                value="{{ old('nama') }}"
                required
            >

        </div>


        <!-- ALAMAT -->

        <div class="form-group">

            <label for="alamat">
                Alamat
            </label>

            <textarea
                name="alamat"
                id="alamat"
                placeholder="Masukkan alamat lengkap"
                required
            >{{ old('alamat') }}</textarea>

        </div>


        <!-- NO TELEPON -->

        <div class="form-group">

            <label for="no_telpon">
                Nomor Telepon
            </label>

            <input
                type="text"
                name="no_telpon"
                id="no_telpon"
                placeholder="Masukkan nomor telepon"
                value="{{ old('no_telpon') }}"
                required
            >

        </div>


        <!-- EMAIL -->

        <div class="form-group">

            <label for="email">
                Email
            </label>

            <input
                type="email"
                name="email"
                id="email"
                placeholder="Masukkan email"
                value="{{ old('email') }}"
                required
            >

        </div>


        <!-- PASSWORD -->

        <div class="form-group">

            <label for="password">
                Password
            </label>

            <input
                type="password"
                name="password"
                id="password"
                placeholder="Minimal 6 karakter"
                required
            >

        </div>


        <!-- KONFIRMASI PASSWORD -->

        <div class="form-group">

            <label for="password_confirmation">
                Konfirmasi Password
            </label>

            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                placeholder="Masukkan ulang password"
                required
            >

        </div>


        <!-- BUTTON -->

        <button
            type="submit"
            class="btn-register"
        >
            DAFTAR
        </button>


    </form>


    <!-- LOGIN -->

    <div class="login-link">

        <p>

            Sudah punya akun?

            <a href="{{ route('login') }}">
                Login di sini
            </a>

        </p>

    </div>


</div>


</body>

</html>