<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pekerjaan</title>
</head>

<body>

<div style="max-width:700px;margin:40px auto;">

    <h2>Bukti Pekerjaan</h2>

    <p>
        <strong>Pekerjaan:</strong>
        {{ $lamaran->pekerjaan->nama_pekerjaan ?? '-' }}
    </p>

    <p>
        <strong>Status Lamaran:</strong>
        {{ ucfirst($lamaran->status_lamaran ?? '-') }}
    </p>

    @if($errors->any())

        <div style="background:#f8d7da;padding:15px;margin-bottom:15px;">

            <strong>Terdapat kesalahan:</strong>

            <ul>

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    @if(session('success'))

        <div style="background:#d4edda;padding:15px;margin-bottom:15px;">

            {{ session('success') }}

        </div>

    @endif

    <p>
        Silakan upload bukti bahwa pekerjaan telah selesai.
        Setelah bukti dikirim, pemberi kerja akan memeriksa
        dan melakukan pembayaran.
    </p>

    <hr>

    <form
        action="{{ route('pencari.bukti.store', $lamaran->id_lamaran) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        <div style="margin-bottom:15px;">

            <label for="foto_bukti_kerja">
                <strong>Foto/Scan Bukti Pekerjaan</strong>
            </label>

            <br>

            <input
                type="file"
                id="foto_bukti_kerja"
                name="foto_bukti_kerja"
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

        <div style="margin-bottom:15px;">

            <label for="catatan">
                <strong>Catatan</strong>
            </label>

            <br>

            <textarea
                id="catatan"
                name="catatan"
                rows="5"
                maxlength="500"
                style="width:100%;"
                placeholder="Tambahkan catatan mengenai pekerjaan jika diperlukan..."
            >{{ old('catatan') }}</textarea>

        </div>

        <button
            type="submit"
            onclick="return confirm('Yakin bukti pekerjaan sudah benar dan ingin dikirim?')"
        >
            Kirim Bukti Pekerjaan
        </button>

        <a
            href="{{ route('pencari.lamaran-saya') }}"
            style="margin-left:10px;"
        >
            Kembali
        </a>

    </form>

</div>

</body>

</html>