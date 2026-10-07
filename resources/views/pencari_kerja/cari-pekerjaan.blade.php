<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cari Pekerjaan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
        }

        .navbar {
            background: #1f3c88;
            padding: 16px 30px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        .card h3 {
            margin-top: 0;
        }

        .info {
            margin: 8px 0;
            color: #555;
        }

        .btn {
            display: inline-block;
            background: #1f3c88;
            color: white;
            text-decoration: none;
            padding: 9px 14px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .empty {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
        }

        @media (max-width: 800px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="navbar">

    <a href="{{ route('pencari.dashboard') }}">
        Dashboard
    </a>

    <a href="{{ route('pencari.lamaran-saya') }}">
        Lamaran Saya
    </a>

    <a href="{{ route('pencari.profil') }}">
        Profil
    </a>

</div>

<div class="container">

    <h2>Cari Pekerjaan</h2>

    @if($pekerjaan->count() > 0)

        <div class="grid">

            @foreach($pekerjaan as $p)

                <div class="card">

                    <h3>
                        {{ $p->nama_pekerjaan }}
                    </h3>

                    <div class="info">
                        <strong>Keahlian:</strong>
                        {{ $p->keahlian->nama_keahlian ?? '-' }}
                    </div>

                    <div class="info">
                        <strong>Pemberi:</strong>
                        {{ $p->pemberiKerja->nama ?? '-' }}
                    </div>

                    <div class="info">
                        <strong>Lokasi:</strong>
                        {{ $p->lokasi ?? '-' }}
                    </div>

                    @if(isset($p->jarak_km))

                        <div class="info">
                            <strong>Jarak:</strong>
                            {{ number_format($p->jarak_km, 2) }} km
                        </div>

                    @endif

                    <div class="info">
                        <strong>Upah:</strong>
                        Rp {{ number_format($p->upah ?? 0, 0, ',', '.') }}
                    </div>

                    <div class="info">
                        <strong>Jumlah pekerja:</strong>
                        {{ $p->jumlah_pekerja }}
                    </div>

                    <a
                        href="{{ route('pekerjaan.show', $p->id_pekerjaan) }}"
                        class="btn"
                    >
                        Lihat Detail
                    </a>

                </div>

            @endforeach

        </div>

    @else

        <div class="empty">

            <h3>
                Tidak ada pekerjaan yang tersedia.
            </h3>

            <p>
                Belum ada pekerjaan yang sesuai dengan
                keahlian terverifikasi dan lokasi kamu.
            </p>

        </div>

    @endif

</div>

</body>
</html>