<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            margin-bottom: 12px;
            border-radius: 8px;
        }

        .baru {
            border-left: 5px solid #1f3c88;
        }

        .tanggal {
            color: #777;
            font-size: 13px;
            margin-top: 8px;
        }

        a {
            text-decoration: none;
            color: #1f3c88;
        }
    </style>
</head>

<body>

<div class="container">

    <a href="{{ route('pencari.dashboard') }}">
        ← Kembali ke Dashboard
    </a>

    <h2>Notifikasi</h2>

    @forelse($notifikasi as $n)

        <div
            class="card {{ in_array($n->id_notifikasi, $baru) ? 'baru' : '' }}"
        >

            <div>
                {{ $n->pesan }}
            </div>

            <div class="tanggal">
                {{ $n->tanggal }}
            </div>

        </div>

    @empty

        <div class="card">

            <p>
                Belum ada notifikasi.
            </p>

        </div>

    @endforelse

</div>

</body>
</html>