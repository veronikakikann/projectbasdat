<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $pekerjaan->nama_pekerjaan }}</title>

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
            max-width: 850px;
            margin: 30px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        .info {
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
        }

        .description {
            white-space: pre-line;
            line-height: 1.6;
        }

        .btn {
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .primary {
            background: #1f3c88;
            color: white;
        }

        .secondary {
            background: #6c757d;
            color: white;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>

<div class="navbar">

    <a href="{{ route('pencari.dashboard') }}">
        Dashboard
    </a>

    <a href="{{ route('pencari.cari-pekerjaan') }}">
        Cari Pekerjaan
    </a>

    <a href="{{ route('pencari.lamaran-saya') }}">
        Lamaran Saya
    </a>

</div>

<div class="container">

    @if(session('success'))
        <div class="alert success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert error">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">

        <h2>
            {{ $pekerjaan->nama_pekerjaan }}
        </h2>

        <div class="info">
            <span class="label">Pemberi Kerja:</span>
            {{ $pekerjaan->pemberiKerja->nama ?? '-' }}
        </div>

        <div class="info">
            <span class="label">Keahlian:</span>
            {{ $pekerjaan->keahlian->nama_keahlian ?? '-' }}
        </div>

        <div class="info">
            <span class="label">Lokasi:</span>
            {{ $pekerjaan->lokasi ?? '-' }}
        </div>

        <div class="info">
            <span class="label">Tanggal Pekerjaan:</span>
            {{ $pekerjaan->tanggal_pengerjaan ?? '-' }}
        </div>

        <div class="info">
            <span class="label">Jumlah Pekerja:</span>
            {{ $pekerjaan->jumlah_pekerja }}
        </div>

        <div class="info">
            <span class="label">Upah:</span>
            Rp {{ number_format($pekerjaan->upah ?? 0, 0, ',', '.') }}
        </div>

        <hr>

        <h3>Deskripsi Pekerjaan</h3>

        <div class="description">
            {{ $pekerjaan->deskripsi ?? '-' }}
        </div>

        <h3>Syarat Pekerjaan</h3>

        <div class="description">
            {{ $pekerjaan->persyaratan ?? '-' }}
        </div>

        <br>

        <a
            href="{{ route('pencari.cari-pekerjaan') }}"
            class="btn secondary"
        >
            Kembali
        </a>

        @if($pekerjaan->status_pekerjaan === 'tersedia')

            <form
                action="{{ route('pencari.lamar', $pekerjaan->id_pekerjaan) }}"
                method="POST"
                style="display:inline;"
            >

                @csrf

                <button
                    type="submit"
                    class="btn primary"
                    onclick="return confirm('Yakin ingin melamar pekerjaan ini?')"
                >
                    Lamar Pekerjaan
                </button>

            </form>

        @endif

    </div>

</div>

</body>
</html>