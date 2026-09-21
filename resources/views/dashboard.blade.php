<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Bursa Kerja Harian</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #2c3e50;
            color: white;
            padding: 25px;
            text-align: center;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .card p {
            color: #666;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        @media (max-width: 800px) {
            .card-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 500px) {
            .card-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>BURSA KERJA HARIAN</h1>
        <p>Dashboard Sistem Bursa Kerja Kasar dan Harian</p>
    </div>

    <div class="container">

        <h2>Menu Data</h2>

        <div class="card-container">

            <!-- ADMIN -->
            <div class="card">
                <h3>Data Admin</h3>
                <p>Kelola data admin sistem.</p>
                <a href="{{ route('admin.index') }}" class="btn">
                    Kelola
                </a>
            </div>

            <!-- PEMBERI KERJA -->
            <div class="card">
                <h3>Data Pemberi Kerja</h3>
                <p>Kelola data pemberi kerja.</p>
                <a href="{{ route('pemberi_kerja.index') }}" class="btn">
                    Kelola
                </a>
            </div>

            <!-- PENCARI KERJA -->
            <div class="card">
                <h3>Data Pencari Kerja</h3>
                <p>Kelola data pencari kerja.</p>
                <a href="{{ route('pencari_kerja.index') }}" class="btn">
                    Kelola
                </a>
            </div>

            <!-- KEAHLIAN -->
            <div class="card">
                <h3>Data Keahlian</h3>
                <p>Kelola daftar keahlian pekerjaan.</p>
                <a href="{{ route('keahlian.index') }}" class="btn">
                    Kelola
                </a>
            </div>

            <!-- KEAHLIAN PENCARI KERJA -->
            <div class="card">
                <h3>Keahlian Pencari Kerja</h3>
                <p>Kelola keahlian yang dimiliki pencari kerja.</p>
                <a href="{{ route('keahlian_pencari_kerja.index') }}" class="btn">
                    Kelola
                </a>
            </div>

            <!-- PEKERJAAN -->
            <div class="card">
                <h3>Data Pekerjaan</h3>
                <p>Kelola data pekerjaan yang tersedia.</p>
                <a href="{{ route('pekerjaan.index') }}" class="btn">
                    Kelola
                </a>
            </div>

            <!-- LAMARAN -->
            <div class="card">
                <h3>Data Lamaran</h3>
                <p>Kelola data lamaran pekerjaan.</p>
                <a href="{{ route('lamaran.index') }}" class="btn">
                    Kelola
                </a>
            </div>

            <!-- BUKTI PENYELESAIAN -->
            <div class="card">
                <h3>Bukti Penyelesaian</h3>
                <p>Kelola bukti penyelesaian pekerjaan.</p>
                <a href="{{ route('bukti_penyelesaian.index') }}" class="btn">
                    Kelola
                </a>
            </div>

            <!-- RATING -->
            <div class="card">
                <h3>Data Rating</h3>
                <p>Kelola rating dan ulasan pengguna.</p>
                <a href="{{ route('rating.index') }}" class="btn">
                    Kelola
                </a>
            </div>

            <!-- NOTIFIKASI -->
            <div class="card">
                <h3>Data Notifikasi</h3>
                <p>Kelola data notifikasi pengguna.</p>
                <a href="{{ route('notifikasi.index') }}" class="btn">
                    Kelola
                </a>
            </div>

        </div>

    </div>

</body>
</html>