<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rating Pemberi Kerja</title>
</head>

<body>

<div style="max-width:700px;margin:40px auto;">

    <h2>
        Rating Pemberi Kerja
    </h2>

    <p>
        <strong>Pekerjaan:</strong>
        {{ $lamaran->pekerjaan->nama_pekerjaan }}
    </p>

    @if($rating)

        <p>
            Kamu sudah memberikan rating.
            Kamu masih dapat mengedit rating selama
            belum melewati batas waktu.
        </p>

        <form
            action="{{ route(
                'pencari.rating.update',
                $rating->id_rating
            ) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

    @else

        <form
            action="{{ route(
                'pencari.rating.store',
                $lamaran->id_lamaran
            ) }}"
            method="POST"
        >

            @csrf

    @endif

        <div style="margin-bottom:15px;">

            <label>
                Skor Rating
            </label>

            <select
                name="skor"
                required
            >

                <option value="">
                    Pilih skor
                </option>

                @for($i = 1; $i <= 5; $i++)

                    <option
                        value="{{ $i }}"
                        @selected(
                            old(
                                'skor',
                                $rating->skor ?? ''
                            ) == $i
                        )
                    >
                        {{ $i }} - {{ $i == 1 ? 'Sangat Buruk' : ($i == 5 ? 'Sangat Baik' : '') }}
                    </option>

                @endfor

            </select>

        </div>

        <div style="margin-bottom:15px;">

            <label>
                Komentar
            </label>

            <br>

            <textarea
                name="kategori_komentar"
                style="width:100%;height:100px;"
            >{{ old(
                'kategori_komentar',
                $rating->kategori_komentar ?? ''
            ) }}</textarea>

        </div>

        <button type="submit">
            {{ $rating ? 'Perbarui Rating' : 'Kirim Rating' }}
        </button>

        <a href="{{ route('pencari.lamaran-saya') }}">
            Kembali
        </a>

    </form>

</div>

</body>
</html>