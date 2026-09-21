<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Dashboard Pencari Kerja</title>

    <style>

        body {
            margin: 0;

            font-family: Arial, sans-serif;

            background: #f4f6f9;
        }

        .header {
            background: #27ae60;

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

            background: #27ae60;

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

    <h1>Dashboard Pencari Kerja</h1>

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

    <h2>Menu Pencari Kerja</h2>


    <div class="menu">


        <div class="card">

            <h3>Cari Pekerjaan</h3>

            <p>
                Lihat pekerjaan yang tersedia.
            </p>

            <a
                href="{{ route('pekerjaan.index') }}"
                class="btn"
            >
                Cari Pekerjaan
            </a>

        </div>


        <div class="card">

            <h3>Lamaran Saya</h3>

            <p>
                Lihat status lamaran pekerjaan.
            </p>

            <a
                href="{{ route('lamaran.index') }}"
                class="btn"
            >
                Lihat Lamaran
            </a>

        </div>


        <div class="card">

            <h3>Keahlian Saya</h3>

            <p>
                Kelola keahlian yang dimiliki.
            </p>

            <a
                href="{{ route('keahlian_pencari_kerja.index') }}"
                class="btn"
            >
                Kelola Keahlian
            </a>

        </div>


        <div class="card">

            <h3>Bukti Penyelesaian</h3>

            <p>
                Upload dan lihat bukti pekerjaan.
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
                Berikan rating kepada pemberi kerja.
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