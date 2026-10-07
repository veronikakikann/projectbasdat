<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Pelamar</title>
</head>

<body>

    <h1>Daftar Pelamar</h1>

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

    @if($lamaran->count() > 0)

        <table border="1" cellpadding="8" style="border-collapse: collapse;">

            <tr>
                <th>Pelamar</th>
                <th>Pekerjaan</th>
                <th>Tanggal Melamar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            @foreach($lamaran as $l)

                <tr>

                    <td>
                        {{ $l->pencariKerja->nama ?? '-' }}
                    </td>

                    <td>
                        {{ $l->pekerjaan->nama_pekerjaan ?? '-' }}
                    </td>

                    <td>
                        {{ $l->tanggal_submit ?? '-' }}
                    </td>

                    <td>
                        {{ ucfirst($l->status_lamaran) }}
                    </td>

                    <td>

                        @if($l->status_lamaran === 'menunggu')

                            <form
                                action="{{ route('pemberi.lamaran.update', $l->id_lamaran) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PUT')

                                <input
                                    type="hidden"
                                    name="status_lamaran"
                                    value="diterima"
                                >

                                <button type="submit">
                                    Terima
                                </button>
                            </form>

                            <br>

                            <form
                                action="{{ route('pemberi.lamaran.update', $l->id_lamaran) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PUT')

                                <input
                                    type="hidden"
                                    name="status_lamaran"
                                    value="ditolak"
                                >

                                <button type="submit">
                                    Tolak
                                </button>
                            </form>

                        @elseif($l->status_lamaran === 'diterima')

                            <span>
                                Pelamar diterima
                            </span>

                        @elseif($l->status_lamaran === 'ditolak')

                            <span>
                                Pelamar ditolak
                            </span>

                        @else

                            -

                        @endif

                    </td>

                </tr>

            @endforeach

        </table>

    @else

        <p>
            Belum ada pelamar.
        </p>

    @endif

</body>

</html>