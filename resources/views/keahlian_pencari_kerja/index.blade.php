<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keahlian Saya</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .primary {
            background: #1f3c88;
            color: white;
        }

        .warning {
            background: #ffc107;
            color: #212529;
        }

        .danger {
            background: #dc3545;
            color: white;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            margin-top: 20px;
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
            font-weight: bold;
        }

        .success {
            color: #155724;
        }

        .pending {
            color: #856404;
        }

        .rejected {
            color: #721c24;
        }

        .alert {
            padding: 12px;
            margin-top: 15px;
            border-radius: 6px;
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">

        <h2>Keahlian Saya</h2>

        <a
            href="{{ route('keahlian_pencari_kerja.create') }}"
            class="btn primary"
        >
            + Ajukan Keahlian
        </a>

    </div>

    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert"
             style="background:#f8d7da;color:#721c24;">
            {{ session('error') }}
        </div>
    @endif

    <table>

        <thead>
            <tr>
                <th>Judul Keahlian</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Tanggal Pengajuan</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

        @forelse($data as $d)

            <tr>

                <td>
                    {{ $d->judul_keahlian }}
                </td>

                <td>
                    {{ $d->keahlian->nama_keahlian ?? 'Menunggu kategori' }}
                </td>

                <td>
                    {{ $d->deskripsi_keahlian }}
                </td>

                <td>

                    @if($d->status_verifikasi_keahlian === 'terverifikasi')

                        <span class="status success">
                            Terverifikasi
                        </span>

                    @elseif($d->status_verifikasi_keahlian === 'ditolak')

                        <span class="status rejected">
                            Ditolak
                        </span>

                    @else

                        <span class="status pending">
                            Menunggu Verifikasi
                        </span>

                    @endif

                </td>

                <td>
                    {{ $d->tanggal_upload }}
                </td>

                <td>

                    @if(in_array(
                        $d->status_verifikasi_keahlian,
                        ['menunggu', 'ditolak']
                    ))

                        <a
                            href="{{ route(
                                'keahlian_pencari_kerja.edit',
                                $d->id_keahlian_pencari
                            ) }}"
                            class="btn warning"
                        >
                            Edit
                        </a>

                        <form
                            action="{{ route(
                                'keahlian_pencari_kerja.destroy',
                                $d->id_keahlian_pencari
                            ) }}"
                            method="POST"
                            style="display:inline;"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn danger"
                                onclick="return confirm('Hapus pengajuan keahlian ini?')"
                            >
                                Hapus
                            </button>

                        </form>

                    @else

                        -

                    @endif

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="6" style="text-align:center;">
                    Belum ada keahlian yang diajukan.
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>

</body>
</html>