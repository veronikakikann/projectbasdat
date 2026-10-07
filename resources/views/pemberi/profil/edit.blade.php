<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Pemberi Kerja</title>
</head>
<body>

<h1>Edit Profil</h1>

<a href="{{ route('pemberi.profil.show') }}">← Kembali ke Profil</a>

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

<form
    action="{{ route('pemberi.profil.update') }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf
    @method('PUT')

    <div>
        <label>NIK</label><br>

        {{-- NIK tidak boleh diubah --}}
        <input
            type="text"
            value="{{ $user->nik }}"
            readonly
        >

        <p>
            <small>NIK tidak dapat diubah karena digunakan untuk verifikasi identitas.</small>
        </p>
    </div>

    <br>

    <div>
        <label for="nama">Nama</label><br>
        <input
            type="text"
            id="nama"
            name="nama"
            value="{{ old('nama', $user->nama) }}"
            required
        >
    </div>

    <br>

    <div>
        <label for="email">Email</label><br>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email', $user->email) }}"
            required
        >
    </div>

    <br>

    <div>
        <label for="no_telpon">No. Telepon</label><br>
        <input
            type="text"
            id="no_telpon"
            name="no_telpon"
            value="{{ old('no_telpon', $user->no_telpon) }}"
        >
    </div>

    <br>

    <div>
        <label for="alamat">Alamat</label><br>
        <textarea
            id="alamat"
            name="alamat"
            rows="4"
        >{{ old('alamat', $user->alamat) }}</textarea>
    </div>

    <br>

    <div>
        <label for="latitude">Latitude</label><br>
        <input
            type="text"
            id="latitude"
            name="latitude"
            value="{{ old('latitude', $user->latitude) }}"
        >
    </div>

    <br>

    <div>
        <label for="longitude">Longitude</label><br>
        <input
            type="text"
            id="longitude"
            name="longitude"
            value="{{ old('longitude', $user->longitude) }}"
        >
    </div>

    <br>

    <div>
        <label for="foto_profil">Foto Profil</label><br>
        <input
            type="file"
            id="foto_profil"
            name="foto_profil"
            accept=".jpg,.jpeg,.png"
        >

        @if($user->foto_profil)
            <p>Foto saat ini:</p>

            <img
                src="{{ route('pemberi.profil.foto') }}"
                alt="Foto Profil"
                width="120"
                height="120"
                style="object-fit: cover;"
            >
        @endif
    </div>

    <br>

    <div>
        <label for="password">Password Baru</label><br>
        <input
            type="password"
            id="password"
            name="password"
        >

        <p>
            <small>Kosongkan jika tidak ingin mengganti password.</small>
        </p>
    </div>

    <br>

    <div>
        <label for="password_confirmation">Konfirmasi Password Baru</label><br>
        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
        >
    </div>

    <br>

    <button type="submit">Simpan Perubahan</button>

</form>

</body>
</html>