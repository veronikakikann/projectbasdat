<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pekerjaan</title>
</head>
<body>

    <h1>Detail Pekerjaan</h1>

    <a href="{{ route('pencari.cari-pekerjaan') }}">
        ← Kembali ke Cari Pekerjaan
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

    <h2>{{ $pekerjaan->nama_pekerjaan }}</h2>

    <p>
        <strong>Pemberi Kerja:</strong>
        {{ $pekerjaan->pemberiKerja->nama ?? '-' }}
    </p>

    <p>
        <strong>Jenis Pekerjaan:</strong>
        {{ $pekerjaan->keahlian->nama_keahlian ?? '-' }}
    </p>

    <p>
        <strong>Lokasi:</strong>
        {{ $pekerjaan->lokasi ?? '-' }}
    </p>

    <p>
        <strong>Deskripsi:</strong><br>
        {{ $pekerjaan->deskripsi ?? '-' }}
    </p>

    <p>
        <strong>Persyaratan:</strong><br>
        {{ $pekerjaan->persyaratan ?? '-' }}
    </p>

    <p>
        <strong>Tanggal Pengerjaan:</strong>
        {{ $pekerjaan->tanggal_pengerjaan ?? '-' }}
    </p>

    <p>
        <strong>Jumlah Pekerja Dibutuhkan:</strong>
        {{ $pekerjaan->jumlah_pekerja }} orang
    </p>

    <p>
        <strong>Upah:</strong>
        Rp {{ number_format($pekerjaan->upah, 0, ',', '.') }}
    </p>

    <p>
        <strong>Status:</strong>
        {{ $pekerjaan->status_pekerjaan }}
    </p>

    @if($pekerjaan->status_pekerjaan === 'tersedia')
        <form
            action="{{ route('pencari.lamar', $pekerjaan->id_pekerjaan) }}"
            method="POST"
        >
            @csrf

            <button type="submit">
                Lamar Pekerjaan
            </button>
        </form>
    @else
        <p style="color: red;">
            Pekerjaan ini sudah tidak tersedia.
        </p>
    @endif

</body>
</html>