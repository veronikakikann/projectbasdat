<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
            padding: 30px 40px;
        }

        .header h1 {
            margin: 0 0 8px 0;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .summary-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .summary-card h3 {
            margin-top: 0;
            color: #555;
        }

        .number {
            font-size: 30px;
            font-weight: bold;
            color: #27ae60;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
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

        .status {
            font-weight: bold;
        }

        .status-menunggu {
            color: #f39c12;
        }

        .status-diterima {
            color: #27ae60;
        }

        .status-ditolak {
            color: #e74c3c;
        }
    </style>

</head>

<body>

    <div class="header">

        <h1>Dashboard Pencari Kerja</h1>

        <p>
            Selamat datang,
            {{ $pencari->nama }}
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

        <div class="summary">

            <div class="summary-card">

                <h3>Pekerjaan Tersedia</h3>

                <div class="number">
                    {{ $jumlahPekerjaanTersedia }}
                </div>

                <p>
                    pekerjaan sedang tersedia di sistem.
                </p>

            </div>


            <div class="summary-card">

                <h3>Lamaran Terakhir</h3>

                @if($lamaranTerakhir)

                    <p>
                        <strong>
                            {{ $lamaranTerakhir->pekerjaan->nama_pekerjaan ?? '-' }}
                        </strong>
                    </p>

                    <p>
                        Status:

                        @if($lamaranTerakhir->status_lamaran === 'menunggu')

                            <span class="status status-menunggu">
                                Menunggu
                            </span>

                        @elseif($lamaranTerakhir->status_lamaran === 'diterima')

                            <span class="status status-diterima">
                                Diterima
                            </span>

                        @else

                            <span class="status status-ditolak">
                                Ditolak
                            </span>

                        @endif

                    </p>

                @else

                    <p>
                        Belum ada lamaran.
                    </p>

                @endif

            </div>

        </div>


        <h2>Menu Pencari Kerja</h2>


        <div class="menu">


            <div class="card">

                <h3>Cari Pekerjaan</h3>

                <p>
                    Lihat pekerjaan yang cocok dengan keahlian
                    dan lokasi kamu.
                </p>

                <a
                    href="{{ route('pencari.cari-pekerjaan') }}"
                    class="btn"
                >
                    Cari Pekerjaan
                </a>

            </div>


            <div class="card">

                <h3>Lamaran Saya</h3>

                <p>
                    Lihat pekerjaan yang sudah kamu lamar
                    dan statusnya.
                </p>

                <a
                    href="{{ route('pencari.lamaran-saya') }}"
                    class="btn"
                >
                    Lihat Lamaran
                </a>

            </div>


            <div class="card">

                <h3>Keahlian Saya</h3>

                <p>
                    Ajukan keahlian dan bukti rekomendasi
                    untuk diverifikasi Admin.
                </p>

                <a
                    href="{{ route('keahlian_pencari_kerja.index') }}"
                    class="btn"
                >
                    Kelola Keahlian
                </a>

            </div>


            <div class="card">

                <h3>Profil Saya</h3>

                <p>
                    Lihat dan ubah data pribadi serta lokasi.
                </p>

                <a
                    href="{{ route('pencari.profil') }}"
                    class="btn"
                >
                    Lihat Profil
                </a>

            </div>


        </div>

    </div>

</body>

</html>