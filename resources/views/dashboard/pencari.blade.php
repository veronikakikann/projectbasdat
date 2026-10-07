<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pencari Kerja</title>

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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        .card h3 {
            margin-top: 0;
        }

        .number {
            font-size: 32px;
            font-weight: bold;
            color: #1f3c88;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .menu a {
            background: #1f3c88;
            color: white;
            text-decoration: none;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
        }

        .logout {
            background: #dc3545;
            border: none;
            color: white;
            padding: 8px 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        .alert {
            padding: 12px;
            background: #d4edda;
            color: #155724;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        @media (max-width: 700px) {
            .cards,
            .menu {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <div>
        <strong>Sistem Bursa Kerja</strong>
    </div>

    <div>
        <a href="{{ route('pencari.dashboard') }}">Dashboard</a>
        <a href="{{ route('pencari.profil') }}">Profil</a>

        <form action="{{ route('logout') }}"
              method="POST"
              style="display:inline;">
            @csrf
            <button type="submit" class="logout">
                Logout
            </button>
        </form>
    </div>
</div>

<div class="container">

    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert" style="background:#f8d7da;color:#721c24;">
            {{ session('error') }}
        </div>
    @endif

    <div class="welcome">
        <h2>
            Selamat datang, {{ $pencari->nama }}
        </h2>

        <p>
            Temukan pekerjaan harian yang sesuai dengan keahlianmu.
        </p>
    </div>

    <div class="cards">

        <div class="card">
            <h3>Pekerjaan Tersedia</h3>

            <div class="number">
                {{ $jumlahPekerjaanTersedia }}
            </div>

            <p>
                pekerjaan tersedia saat ini
            </p>
        </div>

        <div class="card">
            <h3>Lamaran Terakhir</h3>

            @if($lamaranTerakhir)
                <strong>
                    {{ $lamaranTerakhir->pekerjaan->nama_pekerjaan ?? '-' }}
                </strong>

                <p>
                    Status:
                    {{ ucfirst($lamaranTerakhir->status_lamaran) }}
                </p>
            @else
                <p>
                    Belum ada lamaran.
                </p>
            @endif
        </div>

    </div>

    <div class="menu">

        <a href="{{ route('pencari.cari-pekerjaan') }}">
            Cari Pekerjaan
        </a>

        <a href="{{ route('pencari.lamaran-saya') }}">
            Lamaran Saya
        </a>

        <a href="{{ route('keahlian_pencari_kerja.index') }}">
            Keahlian Saya
        </a>

        <a href="{{ route('pencari.profil') }}">
            Profil
        </a>

        <a href="{{ route('pencari.notifikasi') }}">
            Notifikasi
        </a>

    </div>

</div>

</body>
</html>