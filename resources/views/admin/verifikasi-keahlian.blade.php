<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Keahlian Pencari</title>
</head>
<body>

    <h1>Verifikasi Keahlian Pencari Kerja</h1>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    {{-- Pesan error --}}
    @if(session('error'))
        <p style="color: red;">
            {{ session('error') }}
        </p>
    @endif

    {{-- Error validasi --}}
    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <br>

    @if($data->count() > 0)

        <table border="1" cellpadding="8" style="border-collapse: collapse;">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pencari</th>
                    <th>Judul Keahlian</th>
                    <th>Deskripsi</th>
                    <th>Surat Rekomendasi</th>
                    <th>Kategori Keahlian</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($data as $item)

                    <tr>
                        <td>
                            {{ $item->id_keahlian_pencari }}
                        </td>

                        <td>
                            {{ $item->pencariKerja->nama ?? '-' }}
                        </td>

                        <td>
                            {{ $item->judul_keahlian }}
                        </td>

                        <td>
                            {{ $item->deskripsi_keahlian ?? '-' }}
                        </td>

                        <td>
                            @if($item->file_surat_rekomendasi)
                                <a
                                    href="{{ asset('storage/' . $item->file_surat_rekomendasi) }}"
                                    target="_blank"
                                >
                                    Lihat File
                                </a>
                            @else
                                Tidak ada file
                            @endif
                        </td>

                        <td>
                            @if($item->keahlian)
                                {{ $item->keahlian->nama_keahlian }}
                            @else
                                Belum ditentukan
                            @endif
                        </td>

                        <td>

                            <form
                                action="{{ route('admin.verifikasi-keahlian.keputusan', $item->id_keahlian_pencari) }}"
                                method="POST"
                            >

                                @csrf
                                @method('PATCH')

                                <label>Status:</label>
                                <br>

                                <select
                                    name="status_verifikasi_keahlian"
                                    required
                                >
                                    <option value="">
                                        -- Pilih Status --
                                    </option>

                                    <option value="terverifikasi">
                                        Verifikasi
                                    </option>

                                    <option value="ditolak">
                                        Tolak
                                    </option>
                                </select>

                                <br><br>

                                <label>Kategori Keahlian:</label>
                                <br>

                                <select name="id_keahlian">
                                    <option value="">
                                        -- Pilih Kategori --
                                    </option>

                                    @foreach($keahlian as $k)
                                        <option
                                            value="{{ $k->id_keahlian }}"
                                        >
                                            {{ $k->nama_keahlian }}
                                        </option>
                                    @endforeach

                                </select>

                                <br><br>

                                <button
                                    type="submit"
                                    onclick="return confirm('Yakin ingin menyimpan keputusan verifikasi ini?')"
                                >
                                    Simpan Keputusan
                                </button>

                            </form>

                        </td>
                    </tr>

                @endforeach
            </tbody>

        </table>

    @else

        <p>
            Tidak ada pengajuan keahlian yang menunggu verifikasi.
        </p>

    @endif

    <br>

    <a href="{{ route('admin.dashboard') }}">
        Kembali ke Dashboard
    </a>

</body>
</html>