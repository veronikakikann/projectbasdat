<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran</title>
</head>

<body>

<h1>Bukti Pembayaran</h1>

<a href="{{ route('pemberi.pekerjaan.show', $pekerjaan->id_pekerjaan) }}">
    ← Kembali ke Detail Pekerjaan
</a>

<hr>

@if($errors->any())
    <div style="color: red;">
        <strong>Terdapat kesalahan:</strong>

        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
@endif

<h2>{{ $pekerjaan->nama_pekerjaan }}</h2>

<p>
    Status Pekerjaan:
    <strong>
        {{ ucfirst(str_replace('_', ' ', $pekerjaan->status_pekerjaan)) }}
    </strong>
</p>

<hr>

<h3>Daftar Pekerja yang Diterima</h3>

@forelse($lamarans as $lamaran)

    <div style="border:1px solid #ccc; padding:15px; margin-bottom:20px;">

        <p>
            <strong>Nama Pekerja:</strong>
            {{ $lamaran->pencariKerja->nama ?? '-' }}
        </p>

        <p>
            <strong>Status Lamaran:</strong>
            {{ ucfirst($lamaran->status_lamaran) }}
        </p>

        <hr>

        <h4>Bukti Pekerjaan</h4>

        @if($lamaran->buktiPenyelesaian &&
            $lamaran->buktiPenyelesaian->foto_bukti_kerja)

            <p style="color:green;">
                ✓ Pekerja sudah mengunggah bukti pekerjaan.
            </p>

            <a
                href="{{ route(
                    'pemberi.bukti.file',
                    [
                        $lamaran->buktiPenyelesaian->id_bukti,
                        'kerja'
                    ]
                ) }}"
                target="_blank"
            >
                Lihat Bukti Pekerjaan
            </a>

            @if($lamaran->buktiPenyelesaian->catatan)
                <p>
                    <strong>Catatan Pekerja:</strong><br>
                    {{ $lamaran->buktiPenyelesaian->catatan }}
                </p>
            @endif

            <hr>

            @if($lamaran->buktiPenyelesaian->foto_bukti_bayar)

                <p style="color:green;">
                    ✓ Bukti pembayaran sudah diunggah.
                </p>

                <a
                    href="{{ route(
                        'pemberi.bukti.file',
                        [
                            $lamaran->buktiPenyelesaian->id_bukti,
                            'bayar'
                        ]
                    ) }}"
                    target="_blank"
                >
                    Lihat Bukti Pembayaran
                </a>

            @else

                <h4>Upload Bukti Pembayaran</h4>

                <form
                    action="{{ route('pemberi.bukti.store', $pekerjaan->id_pekerjaan) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="id_lamaran"
                        value="{{ $lamaran->id_lamaran }}"
                    >

                    <div>
                        <label for="foto_bukti_bayar_{{ $lamaran->id_lamaran }}">
                            <strong>Bukti Pembayaran</strong>
                        </label>

                        <br>

                        <input
                            type="file"
                            id="foto_bukti_bayar_{{ $lamaran->id_lamaran }}"
                            name="foto_bukti_bayar"
                            accept=".jpg,.jpeg,.png,.pdf"
                            required
                        >

                        <p>
                            <small>
                                Format: JPG, JPEG, PNG, atau PDF.
                                Maksimal 2 MB.
                            </small>
                        </p>
                    </div>

                    <div>
                        <label for="catatan_bayar_{{ $lamaran->id_lamaran }}">
                            <strong>Catatan Pembayaran</strong>
                        </label>

                        <br>

                        <textarea
                            id="catatan_bayar_{{ $lamaran->id_lamaran }}"
                            name="catatan_bayar"
                            rows="4"
                            maxlength="1000"
                            placeholder="Tambahkan catatan pembayaran jika diperlukan..."
                        >{{ old('catatan_bayar') }}</textarea>
                    </div>

                    <br>

                    <button
                        type="submit"
                        onclick="return confirm('Yakin bukti pembayaran sudah benar?')"
                    >
                        Upload Bukti Pembayaran
                    </button>

                </form>

            @endif

        @else

            <p style="color:#856404;">
                Pekerja belum mengunggah bukti pekerjaan.
                Pembayaran belum dapat diproses.
            </p>

        @endif

    </div>

@empty

    <p>
        Belum ada pekerja yang diterima untuk pekerjaan ini.
    </p>

@endforelse

</body>
</html>