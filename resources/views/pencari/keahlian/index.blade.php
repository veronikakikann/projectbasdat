@extends('pencari.layout')

@section('content')
<style>
    .skill-page {
        padding: 30px;
    }

    .skill-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .skill-title {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }

    .skill-subtitle {
        margin: 0;
        color: #777;
        font-size: 14px;
    }

    .skill-add-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 18px;
        background: #FF8A5B;
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        white-space: nowrap;
    }

    .skill-add-btn:hover {
        color: white;
        opacity: .9;
    }

    .skill-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        flex-wrap: wrap;
    }

    .skill-tab {
        padding: 10px 18px;
        border-radius: 10px;
        text-decoration: none;
        color: #666;
        background: #f5f5f5;
        font-weight: 600;
        font-size: 14px;
    }

    .skill-tab:hover {
        color: #FF8A5B;
    }

    .skill-tab.active {
        background: #FF8A5B;
        color: white;
    }

    .skill-list {
        display: grid;
        gap: 18px;
    }

    .skill-card {
        background: white;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
    }

    .skill-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 14px;
    }

    .skill-card-title {
        margin: 0 0 7px;
        font-size: 19px;
        font-weight: 700;
        color: #333;
    }

    .skill-category {
        font-size: 13px;
        color: #777;
    }

    .skill-status {
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .skill-status.menunggu {
        background: #fff4cc;
        color: #a47700;
    }

    .skill-status.terverifikasi {
        background: #dff6e8;
        color: #16834b;
    }

    .skill-status.ditolak {
        background: #fde4e4;
        color: #c0392b;
    }

    .skill-description {
        color: #555;
        line-height: 1.6;
        margin: 0 0 16px;
    }

    .skill-info {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        color: #777;
        font-size: 13px;
        margin-bottom: 16px;
    }

    .skill-proof {
        margin-bottom: 16px;
    }

    .skill-proof a {
        color: #55B4EA;
        text-decoration: none;
        font-weight: 600;
    }

    .skill-proof a:hover {
        text-decoration: underline;
    }

    .skill-actions {
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
    }

    .skill-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 15px;
        border-radius: 9px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
    }

    .skill-btn-view {
        background: #eef8ff;
        color: #2585b8;
    }

    .skill-btn-edit {
        background: #fff1eb;
        color: #d96d40;
    }

    .skill-btn-delete {
        background: #fdecec;
        color: #c0392b;
    }

    .skill-empty {
        background: white;
        border-radius: 14px;
        padding: 45px 25px;
        text-align: center;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
    }

    .skill-empty h3 {
        margin: 0 0 8px;
        color: #444;
    }

    .skill-empty p {
        margin: 0;
        color: #888;
    }

    .skill-alert {
        margin-bottom: 20px;
        padding: 12px 16px;
        border-radius: 10px;
        background: #e8f7ee;
        color: #217346;
        font-size: 14px;
    }

    @media (max-width: 700px) {
        .skill-page {
            padding: 20px;
        }

        .skill-header {
            flex-direction: column;
        }

        .skill-add-btn {
            width: 100%;
            justify-content: center;
        }

        .skill-card-top {
            flex-direction: column;
        }
    }
</style>

<div class="skill-page">

    @if(session('success'))
        <div class="skill-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="skill-header">
        <div>
            <h1 class="skill-title">Keahlian Saya</h1>

            <p class="skill-subtitle">
                Kelola keahlian dan surat rekomendasi yang kamu ajukan.
            </p>
        </div>

        <a
            href="{{ route('keahlian_pencari_kerja.create') }}"
            class="skill-add-btn"
        >
            + Tambah Keahlian
        </a>
    </div>

    <div class="skill-tabs">

        <a
            href="{{ route('keahlian_pencari_kerja.index', ['status' => 'menunggu']) }}"
            class="skill-tab {{ $status === 'menunggu' ? 'active' : '' }}"
        >
            Menunggu Verifikasi
        </a>

        <a
            href="{{ route('keahlian_pencari_kerja.index', ['status' => 'terverifikasi']) }}"
            class="skill-tab {{ $status === 'terverifikasi' ? 'active' : '' }}"
        >
            Sudah Terverifikasi
        </a>

        <a
            href="{{ route('keahlian_pencari_kerja.index', ['status' => 'ditolak']) }}"
            class="skill-tab {{ $status === 'ditolak' ? 'active' : '' }}"
        >
            Ditolak
        </a>

    </div>

    <div class="skill-list">

        @forelse($data as $item)

            @php
                $statusLabel = match ($item->status_verifikasi_keahlian) {
                    'terverifikasi' => 'Terverifikasi',
                    'ditolak' => 'Ditolak',
                    default => 'Menunggu Verifikasi',
                };

                $statusClass = match ($item->status_verifikasi_keahlian) {
                    'terverifikasi' => 'terverifikasi',
                    'ditolak' => 'ditolak',
                    default => 'menunggu',
                };

                $namaKeahlian = $item->keahlian
                    ? $item->keahlian->nama_keahlian
                    : 'Belum ditentukan';
            @endphp

            <div class="skill-card">

                <div class="skill-card-top">

                    <div>
                        <h2 class="skill-card-title">
                            {{ $item->judul_keahlian }}
                        </h2>

                        <div class="skill-category">
                            Kategori:
                            <strong>{{ $namaKeahlian }}</strong>
                        </div>
                    </div>

                    <span class="skill-status {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>

                </div>

                <p class="skill-description">
                    {{ $item->deskripsi_keahlian }}
                </p>

                <div class="skill-info">
                    <span>
                        📅
                        {{ optional($item->tanggal_upload)->format('d M Y') }}
                    </span>

                    @if($item->keahlian)
                        <span>
                            🏷️
                            {{ $item->keahlian->nama_keahlian }}
                        </span>
                    @endif
                </div>

                @if($item->file_surat_rekomendasi)
                    <div class="skill-proof">
                        <a
                            href="{{ route('dokumen.keahlian', ['pengajuan' => $item->id_keahlian_pencari]) }}"
                            target="_blank"
                        >
                            📄 Lihat Bukti / Surat Rekomendasi
                        </a>
                    </div>
                @endif

                @if(in_array($item->status_verifikasi_keahlian, ['menunggu', 'ditolak'], true))
                    <div class="skill-actions">

                        <a
                            href="{{ route('keahlian_pencari_kerja.edit', $item->id_keahlian_pencari) }}"
                            class="skill-btn skill-btn-edit"
                        >
                            Edit
                        </a>

                        <form
                            action="{{ route('keahlian_pencari_kerja.destroy', $item->id_keahlian_pencari) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus pengajuan keahlian ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="skill-btn skill-btn-delete"
                            >
                                Hapus
                            </button>
                        </form>

                    </div>
                @endif

            </div>

        @empty

            <div class="skill-empty">

                @if($status === 'menunggu')
                    <h3>Belum ada keahlian yang menunggu verifikasi</h3>
                    <p>
                        Pengajuan keahlian yang baru kamu kirim akan muncul di sini.
                    </p>

                @elseif($status === 'terverifikasi')
                    <h3>Belum ada keahlian terverifikasi</h3>
                    <p>
                        Keahlian yang sudah disetujui admin akan muncul di sini.
                    </p>

                @else
                    <h3>Belum ada keahlian yang ditolak</h3>
                    <p>
                        Pengajuan yang ditolak admin akan muncul di sini.
                    </p>
                @endif

            </div>

        @endforelse

    </div>

</div>
@endsection