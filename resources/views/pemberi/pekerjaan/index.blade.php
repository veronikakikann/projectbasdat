<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Pekerjaan</title>
</head>

<body>

    <h1>Kelola Pekerjaan</h1>

    <a href="{{ route('pemberi.dashboard') }}">
        ← Kembali ke Dashboard
    </a>

    <hr>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <p>
        <a href="{{ route('pemberi.pekerjaan.create') }}">
            + Tambah Pekerjaan
        </a>
    </p>

    @if($pekerjaan->count() > 0)

        <table border="1" cellpadding="8" style="border-collapse: collapse;">

            <tr>
                <th>Nama Pekerjaan</th>
                <th>Keahlian</th>
                <th>Lokasi</th>
                <th>Upah</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            @foreach($pekerjaan as $p)

                <tr>

                    <td>
                        {{ $p->nama_pekerjaan ?? '-' }}
                    </td>

                    <td>
                        {{ $p->keahlian->nama_keahlian ?? '-' }}
                    </td>

                    <td>
                        {{ $p->lokasi ?? '-' }}
                    </td>

                    <td>
                        Rp {{ number_format($p->upah ?? 0, 0, ',', '.') }}
                    </td>

                    <td>
                        {{ $p->tanggal_pengerjaan ?? '-' }}
                    </td>

                    <td>
                        {{ ucfirst(str_replace('_', ' ', $p->status_pekerjaan ?? '-')) }}
                    </td>

                    <td>

                        <a href="{{ route('pemberi.pekerjaan.show', $p) }}">Detail / Pelamar</a>
                        @if($p->status_pekerjaan === 'tersedia')
                        <a href="{{ route('pemberi.pekerjaan.edit', $p->id_pekerjaan) }}">
                            Edit
                        </a>
                        @endif

                        <br><br>

                        <form
                            action="{{ route('pemberi.pekerjaan.destroy', $p->id_pekerjaan) }}"
                            method="POST"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                onclick="return confirm('Yakin ingin menghapus pekerjaan ini?')"
                            >
                                Hapus
                            </button>
                        </form>

                    </td>

                </tr>

            @endforeach

        </table>

    @else

        <p>
            Kamu belum memiliki pekerjaan.
        </p>

    @endif

</body>

</html>