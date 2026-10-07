<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Pekerjaan</title>
</head>

<body>

    <h1>Tambah Pekerjaan</h1>

    <a href="{{ route('pemberi.pekerjaan.index') }}">
        ← Kembali
    </a>

    <hr>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('pemberi.pekerjaan.store') }}"
        method="POST"
    >
        @csrf

        <p>
            <label>Nama Pekerjaan</label><br>
            <input
                type="text"
                name="nama_pekerjaan"
                value="{{ old('nama_pekerjaan') }}"
                required
            >
        </p>

        <p>
            <label>Keahlian</label><br>

            <select name="id_keahlian" required>
                <option value="">-- Pilih Keahlian --</option>

                @foreach($keahlian as $k)
                    <option
                        value="{{ $k->id_keahlian }}"
                        {{ old('id_keahlian') == $k->id_keahlian ? 'selected' : '' }}
                    >
                        {{ $k->nama_keahlian }}
                    </option>
                @endforeach
            </select>
        </p>

        <p>
            <label>Deskripsi</label><br>
            <textarea
                name="deskripsi"
                rows="5"
                cols="50"
                required
            >{{ old('deskripsi') }}</textarea>
        </p>

        <p>
            <label>Persyaratan</label><br>
            <textarea
                name="persyaratan"
                rows="5"
                cols="50"
            >{{ old('persyaratan') }}</textarea>
        </p>

        <p>
            <label>Lokasi</label><br>
            <input
                type="text"
                name="lokasi"
                value="{{ old('lokasi') }}"
                required
            >
        </p>

        <p>
            <label>Latitude</label><br>
            <input
                type="text"
                name="latitude"
                value="{{ old('latitude') }}"
            >
        </p>

        <p>
            <label>Longitude</label><br>
            <input
                type="text"
                name="longitude"
                value="{{ old('longitude') }}"
            >
        </p>

        <p>
            <label>Upah</label><br>
            <input
                type="number"
                name="upah"
                value="{{ old('upah') }}"
                min="0"
                required
            >
        </p>

        <p>
            <label>Jumlah Pekerja</label><br>
            <input
                type="number"
                name="jumlah_pekerja"
                value="{{ old('jumlah_pekerja', 1) }}"
                min="1"
                required
            >
        </p>

        <p>
            <label>Tanggal Pengerjaan</label><br>
            <input
                type="date"
                name="tanggal_pengerjaan"
                value="{{ old('tanggal_pengerjaan') }}"
                required
            >
        </p>

        <button type="submit">
            Simpan Pekerjaan
        </button>

    </form>

</body>

</html>