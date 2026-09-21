<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Dashboard Pemberi Kerja</title>

    <style>

        body {
            margin: 0;

            font-family: Arial, sans-serif;

            background: #f4f6f9;
        }

        .header {
            background: #3498db;

            color: white;

            padding: 30px;
        }

        .container {
            width: 90%;

            max-width: 1000px;

            margin: 30px auto;
        }

        .menu {
            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 20px;
        }

        .card {
            background: white;

            padding: 25px;

            border-radius: 10px;

            box-shadow:
                0 3px 10px rgba(0,0,0,.08);
        }

        .btn {
            display: inline-block;

            background: #3498db;

            color: white;

            padding: 10px 15px;

            text-decoration: none;

            border-radius: 6px;
        }

        .logout {
            background: #e74c3c;

            color: white;

            border: none;

            padding: 10px 15px;

            border-radius: 6px;

            cursor: pointer;
        }

    </style>

</head>


<body>


<div class="header">

    <h1>Dashboard Pemberi Kerja</h1>

    <p>
        Selamat datang,
        {{ session('user_name') }}
    </p>


    <form
        action="{{ route('logout') }}"
        method="POST"
    >

        @csrf

        <button
            type="submit"
            class="logout"
        >
            Logout
        </button>

    </form>

</div>


<div class="container">

    <h2>Menu Pemberi Kerja</h2>


    <div class="menu">


        <div class="card">

            <h3>Data Pekerjaan</h3>

            <p>
                Buat dan kelola pekerjaan.
            </p>

            <a
                href="{{ route('pekerjaan.index') }}"
                class="btn"
            >
                Kelola Pekerjaan
            </a>

        </div>


        <div class="card">

            <h3>Lamaran</h3>

            <p>
                Lihat lamaran dari pencari kerja.
            </p>

            <a
                href="{{ route('lamaran.index') }}"
                class="btn"
            >
                Lihat Lamaran
            </a>

        </div>


        <div class="card">

            <h3>Bukti Penyelesaian</h3>

            <p>
                Periksa bukti penyelesaian pekerjaan.
            </p>

            <a
                href="{{ route('bukti_penyelesaian.index') }}"
                class="btn"
            >
                Lihat Bukti
            </a>

        </div>


        <div class="card">

            <h3>Rating</h3>

            <p>
                Berikan rating kepada pekerja.
            </p>

            <a
                href="{{ route('rating.index') }}"
                class="btn"
            >
                Kelola Rating
            </a>

        </div>


    </div>

</div>


</body>

</html>