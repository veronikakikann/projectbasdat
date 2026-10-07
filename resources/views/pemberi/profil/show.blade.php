<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pemberi Kerja</title>
</head>
<body>

<h1>Profil Pemberi Kerja</h1>

<a href="{{ route('pemberi.dashboard') }}">← Kembali ke Dashboard</a>

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

@if($user->foto_profil)
    <div>
        <img
            src="{{ route('pemberi.profil.foto') }}"
            alt="Foto Profil"
            width="150"
            height="150"
            style="object-fit: cover;"
        >
    </div>
@endif

<h2>Data Diri</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>NIK</th>
        <td>{{ $user->nik ?? '-' }}</td>
    </tr>

    <tr>
        <th>Nama</th>
        <td>{{ $user->nama ?? '-' }}</td>
    </tr>

    <tr>
        <th>Email</th>
        <td>{{ $user->email ?? '-' }}</td>
    </tr>

    <tr>
        <th>No. Telepon</th>
        <td>{{ $user->no_telpon ?? '-' }}</td>
    </tr>

    <tr>
        <th>Alamat</th>
        <td>{{ $user->alamat ?? '-' }}</td>
    </tr>

    <tr>
        <th>Status Verifikasi</th>
        <td>{{ $user->status_verifikasi ?? '-' }}</td>
    </tr>

    <tr>
        <th>Status Akun</th>
        <td>{{ $user->status_akun ?? '-' }}</td>
    </tr>
</table>

<br>

<a href="{{ route('pemberi.profil.edit') }}">
    <button type="button">Edit Profil</button>
</a>

<hr>

<h2>Statistik</h2>

@if(isset($stat))
<p>Rata-rata: {{ number_format($stat->rata ?? 0, 1) }}/5 · {{ $stat->jumlah ?? 0 }} ulasan</p>
@endif

<hr>

<h2>Ulasan</h2>

@if(isset($ulasan) && $ulasan->count() > 0)

    @foreach($ulasan as $rating)
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">

            <strong>
                {{ $rating->lamaran->pencariKerja->nama ?? 'Pekerja' }}
            </strong>

            <p>
                <strong>Rating:</strong>
                {{ $rating->skor }}/5
            </p>

            <p>
                <strong>Komentar:</strong><br>
                {{ $rating->kategori_komentar ?? '-' }}
            </p>

            <small>
                {{ $rating->tanggal_rating ?? '-' }}
            </small>

        </div>
    @endforeach

@else
    <p>Belum ada ulasan.</p>
@endif

</body>
</html>