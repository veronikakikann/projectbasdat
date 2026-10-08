<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard Admin</title>


    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }


        .header {
            background: #2c3e50;
            color: white;

            padding: 25px 40px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        .header h1 {
            margin: 0;
        }


        .container {
            width: 90%;
            max-width: 1100px;

            margin: 35px auto;
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

            border-radius: 12px;

            box-shadow:
                0 3px 10px rgba(0, 0, 0, .08);
        }


        .card h3 {
            color: #2c3e50;

            margin-top: 0;
        }


        .card p {
            color: #666;

            line-height: 1.5;
        }


        .btn {
            display: inline-block;

            padding: 10px 15px;

            background: #3498db;

            color: white;

            text-decoration: none;

            border-radius: 6px;
        }


        .btn:hover {
            background: #2980b9;
        }


        .logout {
            background: #e74c3c;

            color: white;

            border: none;

            padding: 10px 15px;

            border-radius: 6px;

            cursor: pointer;
        }


        @media (max-width: 700px) {

            .menu {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>


<body>


    {{-- HEADER --}}

    <div class="header">

        <div>

            <h1>Dashboard Admin</h1>

            <p>
                Selamat datang,
                {{ session('user_name') }}
            </p>

        </div>


        <form
            action="{{ route('logout') }}"
            method="POST"
        >

            @csrf

            <button
                type="submit"
                class="logout"
            >
                Kelola
            </a>

        </div>


        <div class="card">

            <h3>Data Pemberi Kerja</h3>

            <a
                href="{{ route('pemberi_kerja.index') }}"
                class="btn"
            >
                Kelola
            </a>

        </div>


        <div class="card">

            <h3>Data Pencari Kerja</h3>

            <a
                href="{{ route('pencari_kerja.index') }}"
                class="btn"
            >
                Kelola
            </a>

        </div>


        <div class="card">

            <h3>Data Keahlian</h3>

            <a
                href="{{ route('keahlian.index') }}"
                class="btn"
            >
                Kelola
            </a>

        </div>


        <div class="card">

            <h3>Keahlian Pencari Kerja</h3>

            <a
                href="{{ route('admin.verifikasi-keahlian') }}"
                class="btn"
            >
                Kelola
            </a>

        </div>


        <div class="card">

            <h3>Data Pekerjaan</h3>

            <a
                href="{{ route('admin.transaksi.index', 'pekerjaan') }}"
                class="btn"
            >
                Kelola
            </a>

        </div>


        <div class="card">

            <h3>Data Lamaran</h3>

            <a
                href="{{ route('admin.transaksi.index', 'lamaran') }}"
                class="btn"
            >
                Kelola
            </a>

        </div>


        <div class="card">

            <h3>Bukti Penyelesaian</h3>

            <a
                href="{{ route('admin.transaksi.index', 'bukti') }}"
                class="btn"
            >
                Kelola
            </a>

        </div>


        <div class="card">

            <h3>Data Rating</h3>

            <a
                href="{{ route('admin.transaksi.index', 'rating') }}"
                class="btn"
            >
                Kelola
            </a>

        </div>


        <div class="card">

            <h3>Data Notifikasi</h3>

            <a
                href="{{ route('admin.transaksi.index', 'notifikasi') }}"
                class="btn"
            >
                Kelola
            </a>

        </div>

        </form>

    </div>


    {{-- ISI DASHBOARD --}}

    <div class="container">

        <h2>Menu Admin</h2>


        <div class="menu">


            {{-- 1. VERIFIKASI PENCARI --}}

            <div class="card">

                <h3>
                    Verifikasi Pencari Kerja
                </h3>

                <p>
                    Memeriksa dan memverifikasi
                    data pencari kerja yang
                    melakukan registrasi.
                </p>

                <a href="{{ route('pencari_kerja.index') }}" class="btn">Verifikasi</a>

            </div>


            {{-- 2. VERIFIKASI PEMBERI --}}

            <div class="card">

                <h3>
                    Verifikasi Pemberi Kerja
                </h3>

                <p>
                    Memeriksa dan memverifikasi
                    data pemberi kerja yang
                    melakukan registrasi.
                </p>

                <a href="{{ route('pemberi_kerja.index') }}" class="btn">Verifikasi</a>

            </div>


            {{-- 3. VERIFIKASI KEAHLIAN --}}

            <div class="card">

                <h3>
                    Verifikasi Keahlian
                </h3>

                <p>
                    Memeriksa dan memverifikasi
                    keahlian yang diajukan
                    oleh pencari kerja.
                </p>

                <a href="{{ route('keahlian_pencari_kerja.index') }}" class="btn">Verifikasi</a>

            </div>


            {{-- 4. KELOLA AKUN --}}

            <div class="card">

                <h3>
                    Kelola Akun
                </h3>

                <p>
                    Mengelola status akun
                    pencari kerja dan
                    pemberi kerja.
                </p>

                <a
                    href="#"
                    class="btn"
                >
                    Kelola Akun
                </a>

            </div>


        </div>

    </div>


</body>

</html>