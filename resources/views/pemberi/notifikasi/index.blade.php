<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Notifikasi</title>
</head>

<body>

    <h1>Notifikasi</h1>

    <a href="{{ route('pemberi.dashboard') }}">
        ← Kembali ke Dashboard
    </a>

    <hr>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if($notifikasi->count() > 0)

        @foreach($notifikasi as $n)

            <div
                style="
                    border: 1px solid #ccc;
                    padding: 12px;
                    margin-bottom: 10px;
                "
            >

                <strong>
                    {{ $n->judul ?? 'Notifikasi' }}
                </strong>

                <p>
                    {{ $n->isi_pesan ?? '-' }}
                </p>

                <small>
                    {{ $n->tanggal_notifikasi ?? '-' }}
                </small>

            </div>

        @endforeach

    @else

        <p>
            Belum ada notifikasi.
        </p>

    @endif

</body>

</html>