<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Penyelesaian</title>
</head>

<body>

<h1>Bukti Penyelesaian</h1>

<a href="{{ route(
    'pemberi.pekerjaan.show',
    $bukti->lamaran->pekerjaan->id_pekerjaan
) }}">
    ← Kembali ke Detail Pekerjaan
</a>

<hr>

<h2>
    {{ $bukti->lamaran->pekerjaan->nama_pekerjaan ?? '-' }}
</h2>

<table border="1" cellpadding="8" cellspacing="0">

    <tr>
        <th>Pekerja</th>
        <td>
            {{ $bukti->lamaran->pencariKerja->nama ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Status Lamaran</th>
        <td>
            {{ ucfirst($bukti->lamaran->status_lamaran ?? '-') }}
        </td>
    </tr>

    <tr>
        <th>Catatan Pekerja</th>
        <td>
            {{ $bukti->catatan ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Tanggal Upload</th>
        <td>
            {{ $bukti->tanggal_upload ?? '-' }}
        </td>
    </tr>

</table>

<hr>

<h3>Bukti Pekerjaan</h3>

@if($bukti->foto_bukti_kerja)

    <a
        href="{{ route(
            'pemberi.bukti.file',
            [
                $bukti->lamaran->id_lamaran,
                'kerja'
            ]
        ) }}"
        target="_blank"
    >
        Lihat Bukti Pekerjaan
    </a>

@else

    <p>
        Tidak ada file bukti pekerjaan.
    </p>

@endif

<hr>

<h3>Bukti Pembayaran</h3>

@if($bukti->foto_bukti_bayar)

    <a
        href="{{ route(
            'pemberi.bukti.file',
            [
                $bukti->lamaran->id_lamaran,
                'bayar'
            ]
        ) }}"
        target="_blank"
    >
        Lihat Bukti Pembayaran
    </a>

@else

    <p>
        Bukti pembayaran belum diunggah.
    </p>

@endif

</body>

</html>