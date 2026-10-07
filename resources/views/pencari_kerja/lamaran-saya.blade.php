<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lamaran Saya</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
        }

        .navbar {
            background: #1f3c88;
            padding: 16px 30px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f1f3f5;
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 13px;
        }

        .menunggu {
            background: #fff3cd;
            color: #856404;
        }

        .diterima {
            background: #d4edda;
            color: #155724;
        }

        .ditolak {
            background: #f8d7da;
            color: #721c24;
        }

        .selesai {
            background: #cce5ff;
            color: #004085;
        }

        button,
        .btn {
            border: none;
            padding: 7px 12px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-primary {
            background: #1f3c88;
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
        }
    </style>
</head>

<body>

<div class="navbar">

    <a href="{{ route('pencari.dashboard') }}">
        Dashboard
    </a>

    <a href="{{ route('pencari.cari-pekerjaan') }}">
        Cari Pekerjaan
    </a>

    <a href="{{ route('pencari.profil') }}">
        Profil
    </a>

</div>

<div class="container">

    <div class="card">

        <h2>Lamaran Saya</h2>

        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert error">
                {{ session('error') }}
            </div>
        @endif

        @if($lamaran->count() > 0)

            <table>

                <thead>
                    <tr>
                        <th>Pekerjaan</th>
                        <th>Pemberi Kerja</th>
                        <th>Keahlian</th>
                        <th>Tanggal Lamaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($lamaran as $item)

                    <tr>

                        <td>
                            {{ $item->pekerjaan->nama_pekerjaan ?? '-' }}
                        </td>

                        <td>
                            {{ $item->pekerjaan->pemberiKerja->nama ?? '-' }}
                        </td>

                        <td>
                            {{ $item->pekerjaan->keahlian->nama_keahlian ?? '-' }}
                        </td>

                        <td>
                            {{ $item->tanggal_submit
                                ? \Carbon\Carbon::parse($item->tanggal_submit)->format('d-m-Y H:i')
                                : '-' }}
                        </td>

                        <td>

                            <span class="status {{ $item->status_lamaran }}">

                                {{ ucfirst($item->status_lamaran) }}

                            </span>

                        </td>

                        <td>
                            @if($item->buktiPenyelesaian)
                                @if($item->buktiPenyelesaian->foto_bukti_kerja)
                                    <a href="{{ route('dokumen.bukti', [$item->buktiPenyelesaian, 'kerja']) }}">Lihat bukti kerja</a>
                                @endif
                                @if($item->buktiPenyelesaian->foto_bukti_bayar)
                                    <a href="{{ route('dokumen.bukti', [$item->buktiPenyelesaian, 'bayar']) }}">Lihat bukti bayar</a>
                                @endif
                            @endif
                            @if($item->status_lamaran === 'menunggu')

                                <form
                                    action="{{ route('pencari.lamaran.batal', $item->id_lamaran) }}"
                                    method="POST"
                                    style="display:inline;"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn-danger"
                                        onclick="return confirm('Batalkan lamaran ini?')"
                                    >
                                        Batalkan
                                    </button>

                                </form>

                            @elseif($item->status_lamaran === 'selesai')

                                <a
                                    href="{{ route('pencari.rating.form', $item->id_lamaran) }}"
                                    class="btn btn-success"
                                >
                                    Beri Rating
                                </a>

                            @elseif($item->status_lamaran === 'diterima' && $item->pekerjaan->status_pekerjaan === 'sedang_dikerjakan')

                                <a
                                    href="{{ route('pencari.bukti.create', $item->id_lamaran) }}"
                                    class="btn btn-primary"
                                >
                                    Bukti Kerja
                                </a>

                            @else

                                -

                            @endif

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        @else

            <div class="empty">
                <p>Belum ada lamaran pekerjaan.</p>

                <a
                    href="{{ route('pencari.cari-pekerjaan') }}"
                    class="btn btn-primary"
                >
                    Cari Pekerjaan
                </a>
            </div>

        @endif

    </div>

</div>

</body>
</html>