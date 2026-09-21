<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Bursa Kerja Harian</title>


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

            padding: 30px;

        }


        .login-box {

            width: 450px;

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

            font-size: 16px;

        }


        .form-group {

            margin-bottom: 20px;

        }


        label {

            display: block;

            margin-bottom: 8px;

            font-weight: bold;

            color: #333;

        }


        input,
        select {

            width: 100%;

            padding: 13px;

            border: 1px solid #ddd;

            border-radius: 9px;

            font-size: 15px;

        }


        input:focus,
        select:focus {

            outline: none;

            border-color: #3498db;

        }


        .btn-login {

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


        .btn-login:hover {

            background: #4285c5;

        }


        .register-link {

            text-align: center;

            margin-top: 22px;

            padding-top: 20px;

            border-top: 1px solid #eee;

        }


        .register-link p {

            color: #777;

            margin: 0;

        }


        .register-link a {

            color: #3498db;

            font-weight: bold;

            text-decoration: none;

        }


        .register-link a:hover {

            text-decoration: underline;

        }


        .success {

            background: #d4edda;

            color: #155724;

            padding: 12px 15px;

            border-radius: 8px;

            margin-bottom: 20px;

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


<div class="login-box">


    <div class="title">

        <h1>BURSA KERJA HARIAN</h1>

        <p>Silakan login untuk melanjutkan</p>

    </div>


    {{-- Pesan berhasil --}}

    @if(session('success'))

        <div class="success">

            {{ session('success') }}

        </div>

    @endif


    {{-- Pesan error --}}

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
        action="{{ route('login.process') }}"
        method="POST"
    >

        @csrf


        <!-- ROLE -->

        <div class="form-group">

            <label for="role">
                Login sebagai
            </label>

            <select
                name="role"
                id="role"
                required
            >

                <option value="">
                    -- Pilih Role --
                </option>

                <option value="admin">
                    Admin
                </option>

                <option value="pemberi_kerja">
                    Pemberi Kerja
                </option>

                <option value="pencari_kerja">
                    Pencari Kerja
                </option>

            </select>

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
                placeholder="Masukkan password"
                required
            >

        </div>


        <button
            type="submit"
            class="btn-login"
        >
            LOGIN
        </button>


    </form>


    <!-- REGISTER -->

    <div class="register-link">

        <p>

            Belum punya akun?

            <a href="{{ route('register') }}">
                Daftar sekarang
            </a>

        </p>

    </div>


</div>


</body>

</html>