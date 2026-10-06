<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lamaran Saya</title>
</head>
<body>

    <h1>Lamaran Saya</h1>

    <a href="{{ route('pencari.dashboard') }}">
        ← Kembali ke Dashboard
    </a>

    <hr>

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

    @if($lamaran->count() > 0)

        <table border="1" cellpadding="8" style="border-collapse: collapse;">

            <tr>
                <th>Nama Pekerjaan</th>
                <th>Lokasi</th>
                <th>Upah</th>
                <th>Tanggal Melamar</th>
                <th>Status Lamaran</th>
            </tr>

            @foreach($lamaran as $l)

                <tr>
                    <td>
                        {{ $l->pekerjaan->nama_pekerjaan ?? '-' }}
                    </td>

                    <td>
                        {{ $l->pekerjaan->lokasi ?? '-' }}
                    </td>

                    <td>
                        Rp {{ number_format($l->pekerjaan->upah ?? 0, 0, ',', '.') }}
                    </td>

                    <td>
                        {{ $l->tanggal_submit }}
                    </td>

                    <td>
                        {{ ucfirst($l->status_lamaran) }}
                    </td>
                </tr>

            @endforeach

        </table>

    @else

        <p>
            Kamu belum memiliki lamaran pekerjaan.
        </p>

        <a href="{{ route('pencari.cari-pekerjaan') }}">
            Cari Pekerjaan
        </a>

    @endif

</body>
</html>