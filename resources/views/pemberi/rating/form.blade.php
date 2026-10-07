<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ $rating ? 'Edit Rating' : 'Beri Rating' }}
    </title>
</head>
<body>

<h1>
    {{ $rating ? 'Edit Rating' : 'Beri Rating' }}
</h1>

<a href="{{ route('pemberi.lamaran.index') }}">
    ← Kembali ke Daftar Lamaran
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

<h2>
    {{ $lamaran->pekerjaan->nama_pekerjaan ?? '-' }}
</h2>

<p>
    <strong>Pekerja:</strong>
    {{ $lamaran->pencariKerja->nama ?? '-' }}
</p>

<p>
    <strong>Status:</strong>
    {{ ucfirst($lamaran->status_lamaran) }}
</p>

@if($rating)

    <p>
        <strong>Rating saat ini:</strong>
        {{ $rating->skor }}/5
    </p>

@endif

<form
    action="{{
        $rating
            ? route('pemberi.rating.update', $rating->id_rating)
            : route('pemberi.rating.store', $lamaran->id_lamaran)
    }}"
    method="POST"
>
    @csrf

    @if($rating)
        @method('PUT')
    @endif

    <input
        type="hidden"
        name="id_lamaran"
        value="{{ $lamaran->id_lamaran }}"
    >

    <div>
        <label for="skor">
            <strong>Rating</strong>
        </label>

        <br>

        <select
            id="skor"
            name="skor"
            required
        >
            <option value="">-- Pilih Rating --</option>

            @for($i = 1; $i <= 5; $i++)

                <option
                    value="{{ $i }}"
                    {{
                        old(
                            'skor',
                            $rating->skor ?? ''
                        ) == $i
                            ? 'selected'
                            : ''
                    }}
                >
                    {{ $i }} / 5
                </option>

            @endfor

        </select>
    </div>

    <br>

    <div>
        <label for="kategori_komentar">
            <strong>Komentar</strong>
        </label>

        <br>

        <textarea
            id="kategori_komentar"
            name="kategori_komentar"
            rows="5"
            maxlength="500"
            placeholder="Tuliskan kategori_komentar..."
        >{{ old('kategori_komentar', $rating->kategori_komentar ?? '') }}</textarea>
    </div>

    <br>

    <button type="submit">
        {{ $rating ? 'Simpan Perubahan' : 'Kirim Rating' }}
    </button>

</form>

</body>
</html>