@extends('pencari.layout')

@section('title', 'Cari Lowongan')

@section('content')

<style>
    .search-header {
        margin-bottom: 1.5rem;
    }

    .search-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 0.4rem;
    }

    .search-desc {
        color: var(--color-ink-soft);
        font-size: 0.9rem;
    }

    .filter-card {
        background: white;
        border: 1px solid var(--color-border);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-row {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 1rem;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 700;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--color-border);
        border-radius: 8px;
        background: white;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--color-primary);
    }

    .btn-search {
        background: var(--color-primary);
        color: white;
        padding: 0.75rem 1.25rem;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.25rem;
        border: 1px solid var(--color-border);
        border-radius: 8px;
        text-decoration: none;
        color: var(--color-ink);
        font-weight: 600;
        background: white;
        margin-left: 0.5rem;
    }

    .job-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .job-card {
        background: white;
        border: 1px solid var(--color-border);
        border-radius: 12px;
        padding: 1.5rem;
        transition: 0.2s;
    }

    .job-card:hover {
        box-shadow: 0 4px 14px rgba(0,0,0,0.05);
    }

    .job-top {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .job-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--color-ink);
        text-decoration: none;
    }

    .job-title:hover {
        color: var(--color-primary);
    }

    .job-employer {
        margin-top: 0.35rem;
        color: var(--color-ink-soft);
        font-size: 0.85rem;
    }

    .job-status {
        display: inline-flex;
        background: #D1FAE5;
        color: #047857;
        padding: 0.3rem 0.7rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .job-info {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem 1.5rem;
        margin-top: 1.25rem;
    }

    .job-info-item {
        font-size: 0.85rem;
        color: var(--color-ink-soft);
    }

    .job-info-item strong {
        color: var(--color-ink);
    }

    .job-description {
        margin-top: 1rem;
        color: var(--color-ink);
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .job-footer {
        margin-top: 1.25rem;
        padding-top: 1rem;
        border-top: 1px solid var(--color-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .job-wage {
        font-weight: 800;
        color: var(--color-primary-dark);
    }

    .btn-detail {
        background: var(--color-primary);
        color: white;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .empty-card {
        background: white;
        border: 1px solid var(--color-border);
        border-radius: 12px;
        text-align: center;
        padding: 3rem 1.5rem;
        color: var(--color-ink-soft);
    }

    @media (max-width: 800px) {
        .filter-row {
            grid-template-columns: 1fr;
        }

        .job-info {
            grid-template-columns: 1fr;
        }

        .job-top {
            flex-direction: column;
        }
    }
</style>

<div class="search-header">

    <h2 class="search-title">
        Cari Lowongan
    </h2>

    <p class="search-desc">
        Temukan pekerjaan yang sesuai dengan keahlian terverifikasi dan lokasi kamu.
    </p>

</div>


<!-- FILTER -->

<div class="filter-card">

    <form
        action="{{ route('pencari.cari-pekerjaan') }}"
        method="GET"
    >

        <div class="filter-row">

            <div class="form-group">

                <label
                    for="lokasi"
                    class="form-label"
                >
                    Lokasi
                </label>

                <input
                    type="text"
                    id="lokasi"
                    name="lokasi"
                    class="form-control"
                    value="{{ request('lokasi') }}"
                    placeholder="Contoh: Surabaya"
                >

            </div>


            <div class="form-group">

                <label
                    for="id_keahlian"
                    class="form-label"
                >
                    Kategori Keahlian
                </label>

                <select
                    id="id_keahlian"
                    name="id_keahlian"
                    class="form-control"
                >

                    <option value="">
                        Semua keahlian saya
                    </option>

                    @foreach($keahlianTerverifikasi as $keahlian)
                        <option
                            value="{{ $keahlian->id_keahlian }}"
                            {{ (string) request('id_keahlian') === (string) $keahlian->id_keahlian ? 'selected' : '' }}
                        >
                            {{ $keahlian->nama_keahlian }}
                        </option>
                    @endforeach

                </select>

            </div>


            <div>

                <button
                    type="submit"
                    class="btn-search"
                >
                    Cari
                </button>

                <a
                    href="{{ route('pencari.cari-pekerjaan') }}"
                    class="btn-reset"
                >
                    Reset
                </a>

            </div>

        </div>

    </form>

</div>


<!-- DAFTAR LOWONGAN -->

<div class="job-list">

    @forelse($pekerjaan as $job)

        <div class="job-card">

            <div class="job-top">

                <div>

                    <a
                        href="{{ route('pekerjaan.show', $job->id_pekerjaan) }}"
                        class="job-title"
                    >
                        {{ $job->nama_pekerjaan }}
                    </a>

                    <div class="job-employer">
                        {{ $job->pemberiKerja->nama ?? 'Pemberi Kerja' }}
                    </div>

                </div>

                <span class="job-status">
                    Tersedia
                </span>

            </div>


            <div class="job-info">

                <div class="job-info-item">
                    📍
                    <strong>Lokasi:</strong>
                    {{ $job->lokasi }}
                </div>

                <div class="job-info-item">
                    🛠️
                    <strong>Keahlian:</strong>
                    {{ $job->keahlian->nama_keahlian ?? '-' }}
                </div>

                <div class="job-info-item">
                    📅
                    <strong>Tanggal:</strong>
                    {{ \Carbon\Carbon::parse($job->tanggal_pengerjaan)->translatedFormat('d F Y') }}
                </div>

                <div class="job-info-item">
                    👥
                    <strong>Pekerja:</strong>
                    {{ $job->jumlah_pekerja }} orang
                </div>

                @if(isset($job->jarak_km))

                    <div class="job-info-item">
                        📏
                        <strong>Jarak:</strong>
                        {{ number_format($job->jarak_km, 1) }} km
                    </div>

                @endif

            </div>


            <div class="job-description">
                {{ \Illuminate\Support\Str::limit($job->deskripsi, 180) }}
            </div>


            <div class="job-footer">

                <div class="job-wage">
                    Rp {{ number_format($job->upah, 0, ',', '.') }}
                </div>

                <a
                    href="{{ route('pekerjaan.show', $job->id_pekerjaan) }}"
                    class="btn-detail"
                >
                    Lihat Detail
                </a>

            </div>

        </div>

    @empty

        <div class="empty-card">

            <div style="font-size:2rem; margin-bottom:0.75rem;">
                🔎
            </div>

            @if($keahlianTerverifikasi->isEmpty())

                <h3 style="color:var(--color-ink); margin-bottom:0.5rem;">
                    Belum ada keahlian terverifikasi
                </h3>

                <p>
                    Lowongan hanya dapat dicocokkan dengan keahlian
                    yang sudah diverifikasi oleh Admin.
                </p>

            @else

                <h3 style="color:var(--color-ink); margin-bottom:0.5rem;">
                    Belum ada lowongan yang sesuai
                </h3>

                <p>
                    Tidak ditemukan lowongan tersedia yang sesuai
                    dengan keahlian dan filter lokasi kamu.
                </p>

            @endif

        </div>

    @endforelse

</div>

@endsection