<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pekerjaan di Sekitarmu</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
        }

        .header {
            background: #27ae60;
            color: white;
            padding: 25px;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 25px auto;
        }

        .back {
            color: white;
            text-decoration: none;
        }

        .job-card {
            background: white;
            padding: 20px;
            margin-bottom: 18px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .job-card h3 {
            margin-top: 0;
        }

        .tag {
            display: inline-block;
            background: #eaf7ef;
            padding: 6px 10px;
            border-radius: 20px;
            margin-bottom: 10px;
        }

        .detail {
            margin: 8px 0;
        }

        .btn {
            display: inline-block;
            background: #27ae60;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 10px;
        }

        .empty {
            background: white;
            padding: 25px;
            border-radius: 12px;
        }
    </style>
</head>

<body>

    <div class="header">

        <h1>Pekerjaan di Sekitarmu</h1>

        <a
            href="{{ route('pencari.dashboard') }}"
            class="back"
        >
            ← Kembali ke Dashboard
        </a>

    </div>

    <div class="container">

        @if(session('success'))
            <p style="color: green;">
                {{ session('success') }}
            </p>
        @endif

        @if(session('error'))
            <p style="color: red;">
                {{ session('error') }}
            </p>
        @endif

        <p>
            Berikut pekerjaan yang sesuai dengan keahlianmu
            dan berada di sekitar lokasimu.
        </p>

        @if($pekerjaan->count() > 0)

            @foreach($pekerjaan as $p)

                <div class="job-card">

                    <span class="tag">
                        {{ $p->keahlian->nama_keahlian ?? 'Pekerjaan' }}
                    </span>

                    <h3>
                        {{ $p->nama_pekerjaan }}
                    </h3>

                    <div class="detail">
                        <strong>Pemberi Kerja:</strong>
                        {{ $p->pemberiKerja->nama ?? '-' }}
                    </div>

                    <div class="detail">
                        <strong>Lokasi:</strong>
                        {{ $p->lokasi ?? '-' }}
                    </div>

                    @if(isset($p->jarak_km))
                        <div class="detail">
                            <strong>Jarak:</strong>
                            {{ number_format($p->jarak_km, 1, ',', '.') }} km
                        </div>
                    @endif

                    <div class="detail">
                        <strong>Upah:</strong>
                        Rp {{ number_format($p->upah, 0, ',', '.') }}
                    </div>

                    <div class="detail">
                        <strong>Dibutuhkan:</strong>
                        {{ $p->jumlah_pekerja }} orang
                    </div>

                    <div class="detail">
                        <strong>Tanggal:</strong>
                        {{ $p->tanggal_pengerjaan ?? '-' }}
                    </div>

                    <a
                        href="{{ route('pekerjaan.show', $p->id_pekerjaan) }}"
                        class="btn"
                    >
                        Lihat Detail
                    </a>

                </div>

            @endforeach

        @else

            <div class="empty">

                <h3>Belum ada pekerjaan yang cocok</h3>

                <p>
                    Saat ini belum ditemukan pekerjaan yang sesuai
                    dengan keahlian dan lokasi kamu.
                </p>

            </div>

        @endif

    </div>

</body>

</html>