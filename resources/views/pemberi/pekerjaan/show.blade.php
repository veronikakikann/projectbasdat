<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pekerjaan</title>
</head>
<body>

<h1>Detail Pekerjaan</h1>

<a href="{{ route('pemberi.pekerjaan.index') }}">
    ← Kembali ke Daftar Pekerjaan
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
<a href="{{ route('pemberi.pelamar.index', $pekerjaan) }}">Lihat semua pelamar</a>

<table border="1" cellpadding="8" cellspacing="0">

    <tr>
        <th>Keahlian</th>
        <td>
            {{ $pekerjaan->keahlian->nama_keahlian ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Deskripsi</th>
        <td>
            {{ $pekerjaan->deskripsi ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Persyaratan</th>
        <td>
            {{ $pekerjaan->persyaratan ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Lokasi</th>
        <td>
            {{ $pekerjaan->lokasi ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Latitude</th>
        <td>
            {{ $pekerjaan->latitude ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Longitude</th>
        <td>
            {{ $pekerjaan->longitude ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Upah</th>
        <td>
            Rp {{ number_format($pekerjaan->upah ?? 0, 0, ',', '.') }}
        </td>
    </tr>

    <tr>
        <th>Jumlah Pekerja</th>
        <td>
            {{ $pekerjaan->jumlah_pekerja ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Jumlah Pelamar</th>
        <td>
            {{ $jumlahPelamar ?? 0 }}
        </td>
    </tr>

    <tr>
        <th>Tanggal Pengerjaan</th>
        <td>
            {{ $pekerjaan->tanggal_pengerjaan ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Status Pekerjaan</th>
        <td>
            {{ ucfirst(str_replace('_', ' ', $pekerjaan->status_pekerjaan ?? '-')) }}
        </td>
    </tr>

</table>

<hr>

<h2>Pekerja Diterima</h2>

@if(isset($pekerja) && $pekerja->count() > 0)

    <table border="1" cellpadding="8" cellspacing="0">

        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Status Lamaran</th>
        </tr>

        @foreach($pekerja as $l)
            <tr>
                <td>
                    {{ $l->pencariKerja->nama ?? '-' }}
                </td>

                <td>
                    {{ $l->pencariKerja->email ?? '-' }}
                </td>

                <td>
                    {{ ucfirst($l->status_lamaran) }}
                    @if($l->status_lamaran === 'selesai')
                        <a href="{{ route('pemberi.rating.form', $l) }}">Beri / Edit Rating</a>
                    @endif
                    @if($l->buktiPenyelesaian)
                        @if($l->buktiPenyelesaian->foto_bukti_kerja)
                            <a href="{{ route('pemberi.bukti.file', [$l->buktiPenyelesaian, 'kerja']) }}">Bukti kerja</a>
                        @endif
                        @if($l->buktiPenyelesaian->foto_bukti_bayar)
                            <a href="{{ route('pemberi.bukti.file', [$l->buktiPenyelesaian, 'bayar']) }}">Bukti bayar</a>
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach

    </table>

@else

    <p>Belum ada pekerja yang diterima.</p>

@endif

<hr>

<h2>Bukti Penyelesaian</h2>
<p>Bukti kerja dan pembayaran ditampilkan pada masing-masing pekerja di atas.</p>
@if(in_array($pekerjaan->status_pekerjaan, ['sedang_dikerjakan', 'selesai'], true))
    <p><a href="{{ route('pemberi.bukti.create', $pekerjaan) }}">Lihat semua bukti dan pembayaran</a></p>
@endif
<hr>

<h2>Rating Saya</h2>

@if(isset($ratingKu) && $ratingKu->count() > 0)

    @foreach($ratingKu as $rating)
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">

            <p>
                <strong>Pekerja:</strong>
                {{ $rating->lamaran->pencariKerja->nama ?? '-' }}
            </p>

            <p>
                <strong>Rating:</strong>
                {{ $rating->skor }}/5
            </p>

            <p>
                <strong>Komentar:</strong>
                {{ $rating->kategori_komentar ?? '-' }}
            </p>

        </div>
    @endforeach

@else

    <p>Belum ada rating.</p>

@endif

<hr>

{{-- Tombol mulai pekerjaan --}}
@if(in_array($pekerjaan->status_pekerjaan, ['tersedia', 'penuh'], true))

    <form
        action="{{ route('pemberi.pekerjaan.mulai', $pekerjaan->id_pekerjaan) }}"
        method="POST"
        style="display:inline;"
    >
        @csrf
        @method('PATCH')

        <button
            type="submit"
            onclick="return confirm('Yakin ingin memulai pekerjaan ini?')"
        >
            Mulai Pekerjaan
        </button>
    </form>

@endif

{{-- Tombol tutup pekerjaan --}}
@if(in_array($pekerjaan->status_pekerjaan, ['tersedia', 'penuh'], true) && $pekerjaan->jumlahDiterima() === 0)

    <form
        action="{{ route('pemberi.pekerjaan.tutup', $pekerjaan->id_pekerjaan) }}"
        method="POST"
        style="display:inline;"
    >
        @csrf
        @method('PATCH')

        <button
            type="submit"
            onclick="return confirm('Yakin ingin menutup pekerjaan ini?')"
        >
            Tutup Pekerjaan
        </button>
    </form>

@endif

{{-- Bukti hanya ketika sedang dikerjakan --}}
@if($pekerjaan->status_pekerjaan === 'sedang_dikerjakan')

    <p>
        <a href="{{ route('pemberi.bukti.create', $pekerjaan->id_pekerjaan) }}">
            <button type="button">
                Upload Bukti Penyelesaian
            </button>
        </a>
    </p>

@endif

</body>
</html>