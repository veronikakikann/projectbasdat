<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Pemberi Kerja</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f6f8;
            color: #222;
        }

        .navbar {
            background: #1f3c88;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        .success {
            background: #dff5e3;
            color: #216e39;
        }

        .error {
            background: #fde2e2;
            color: #a12626;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .card h3 {
            margin-top: 0;
        }

        .number {
            font-size: 30px;
            font-weight: bold;
            margin: 10px 0;
        }

        .section {
            background: white;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border-bottom: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #f0f2f5;
        }

        .btn {
            display: inline-block;
            padding: 9px 14px;
            background: #1f3c88;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 5px;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 5px;
            background: #eee;
        }

        @media (max-width: 800px) {
            .cards {
                grid-template-columns: 1fr;
            }

            .navbar {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            table {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h2>Dashboard Pemberi Kerja</h2>

        <div>
            <a href="{{ route('pemberi.profil.show') }}">Profil</a>
            <a href="{{ route('pemberi.pekerjaan.index') }}">Lowongan Saya</a>
            <a href="{{ route('pemberi.lamaran.index') }}">Pelamar</a>
            <a href="{{ route('pemberi.notifikasi.index') }}">Notifikasi</a>

            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit">
                    Logout
                </button>
            </form>
        </div>
    </div>


    <div class="container">

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan error --}}
        @if(session('error'))
            <div class="alert error">
                {{ session('error') }}
            </div>
        @endif


        {{-- Ringkasan --}}
        <div class="cards">

            <div class="card">
                <h3>Total Pelamar</h3>

                <div class="number">
                    {{ $totalPelamar }}
                </div>

                <a href="{{ route('pemberi.lamaran.index') }}" class="btn">
                    Lihat Pelamar
                </a>
            </div>


            <div class="card">
                <h3>Pelamar Menunggu</h3>

                <div class="number">
                    {{ $pelamarMenunggu }}
                </div>

                <a href="{{ route('pemberi.lamaran.index', ['status' => 'menunggu']) }}" class="btn">
                    Tinjau Lamaran
                </a>
            </div>


            <div class="card">
                <h3>Lowongan Saya</h3>

                <div class="number">
                    {{ $perStatus->sum() }}
                </div>

                <a href="{{ route('pemberi.pekerjaan.index') }}" class="btn">
                    Kelola Lowongan
                </a>
            </div>

        </div>


        {{-- Status lowongan --}}
        <div class="section">

            <h3>Status Lowongan</h3>

            @if($perStatus->isNotEmpty())

                <table>
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($perStatus as $status => $jumlah)
                            <tr>
                                <td>
                                    <span class="status">
                                        {{ ucwords(str_replace('_', ' ', $status)) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $jumlah }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @else

                <p>Belum ada lowongan pekerjaan.</p>

            @endif

        </div>


        {{-- Lowongan terbaru --}}
        <div class="section">

            <h3>Lowongan Terbaru</h3>

            @if($terbaru->count() > 0)

                <table>
                    <thead>
                        <tr>
                            <th>Nama Pekerjaan</th>
                            <th>Status</th>
                            <th>Jumlah Pelamar</th>
                            <th>Menunggu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($terbaru as $p)

                            <tr>

                                <td>
                                    {{ $p->nama_pekerjaan }}
                                </td>

                                <td>
                                    <span class="status">
                                        {{ ucwords(str_replace('_', ' ', $p->status_pekerjaan)) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $p->lamaran_count }}
                                </td>

                                <td>
                                    {{ $p->menunggu_count }}
                                </td>

                                <td>
                                    <a
                                        href="{{ route('pemberi.pekerjaan.show', $p->id_pekerjaan) }}"
                                        class="btn"
                                    >
                                        Detail
                                    </a>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>
                </table>

            @else

                <p>Belum ada lowongan pekerjaan.</p>

                <a
                    href="{{ route('pemberi.pekerjaan.create') }}"
                    class="btn"
                >
                    Buat Lowongan
                </a>

            @endif

        </div>


        {{-- Tombol buat lowongan --}}
        <div class="section">

            <h3>Kelola Lowongan</h3>

            <p>
                Buat dan kelola lowongan pekerjaan untuk mencari pekerja
                yang sesuai.
            </p>

            <a
                href="{{ route('pemberi.pekerjaan.create') }}"
                class="btn"
            >
                + Buat Lowongan Baru
            </a>

        </div>

    </div>

</body>
</html>