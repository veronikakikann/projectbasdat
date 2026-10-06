<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Keahlian Saya</title>
</head>

<body>

    <h1>Keahlian Saya</h1>

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


    <h2>Keahlian Terverifikasi</h2>

    @php
        $keahlianTerverifikasi = $data->filter(function ($d) {
            return $d->status_verifikasi_keahlian === 'terverifikasi'
                && $d->id_keahlian !== null;
        });
    @endphp

    @if($keahlianTerverifikasi->count() > 0)

        @foreach($keahlianTerverifikasi as $d)

            <div
                style="
                    border: 1px solid #ccc;
                    padding: 15px;
                    margin-bottom: 15px;
                "
            >

                <h3>
                    {{ $d->nama_keahlian ?? $d->judul_keahlian }}
                </h3>

                <p>
                    <strong>Deskripsi:</strong><br>
                    {{ $d->deskripsi_keahlian ?? '-' }}
                </p>

                <p style="color: green;">
                    ✓ Terverifikasi
                </p>

            </div>

        @endforeach

    @else

        <p>
            Belum ada keahlian yang terverifikasi.
        </p>

    @endif


    <hr>


    <h2>Pengajuan Keahlian</h2>

    @php
        $pengajuanKeahlian = $data->filter(function ($d) {
            return $d->status_verifikasi_keahlian !== 'terverifikasi';
        });
    @endphp

    @if($pengajuanKeahlian->count() > 0)

        @foreach($pengajuanKeahlian as $d)

            <div
                style="
                    border: 1px solid #ccc;
                    padding: 15px;
                    margin-bottom: 15px;
                "
            >

                <h3>
                    {{ $d->judul_keahlian }}
                </h3>

                <p>
                    <strong>Deskripsi:</strong><br>
                    {{ $d->deskripsi_keahlian ?? '-' }}
                </p>

                <p>
                    <strong>Bukti:</strong>

                    @if($d->file_surat_rekomendasi)
                        <a
                            href="{{ asset('storage/' . $d->file_surat_rekomendasi) }}"
                            target="_blank"
                        >
                            Lihat Bukti
                        </a>
                    @else
                        -
                    @endif
                </p>

                <p>
                    <strong>Status:</strong>

                    @if($d->status_verifikasi_keahlian === 'menunggu')

                        <span style="color: orange;">
                            Menunggu Verifikasi
                        </span>

                    @elseif($d->status_verifikasi_keahlian === 'ditolak')

                        <span style="color: red;">
                            Ditolak
                        </span>

                    @endif
                </p>

                <p>
                    <strong>Tanggal Pengajuan:</strong>
                    {{ $d->tanggal_upload }}
                </p>

            </div>

        @endforeach

    @else

        <p>
            Belum ada pengajuan keahlian.
        </p>

    @endif


    <hr>

    <a href="{{ route('keahlian_pencari_kerja.create') }}">
        + Ajukan Keahlian Baru
    </a>

</body>

</html>