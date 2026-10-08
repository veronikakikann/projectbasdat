@extends('pemberi.layout')

@section('title', 'Lowongan Saya')

@section('content')
<style>
    .page-header-action {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .btn-primary {
        background-color: var(--color-primary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: 0.2s;
    }
    .btn-primary:hover { background-color: var(--color-primary-dark); }

    .table-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table th {
        text-align: left;
        padding: 1.25rem 1.5rem;
        font-size: 0.8rem;
        color: var(--color-ink-soft);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--color-border);
        background-color: #F8FAFC;
    }

    .modern-table td {
        padding: 1.25rem 1.5rem;
        font-size: 0.95rem;
        color: var(--color-ink);
        border-bottom: 1px solid var(--color-border);
        vertical-align: middle;
    }

    .modern-table tr:last-child td { border-bottom: none; }
    .modern-table tr:hover td { background-color: #F8FAFC; }

    .job-title {
        font-weight: 700;
        color: var(--color-ink);
        margin-bottom: 0.25rem;
        display: block;
        text-decoration: none;
    }
    .job-title:hover { color: var(--color-primary); }
    
    .job-meta { font-size: 0.8rem; color: var(--color-ink-soft); }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: capitalize;
    }
    .status-tersedia { background: #EBF8FF; color: #3182CE; }
    .status-penuh { background: #FEF08A; color: #854D0E; }
    .status-selesai { background: #F0FFF4; color: #2F855A; }
    
    .action-links {
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    
    .action-links a {
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        color: var(--color-ink-soft);
        transition: 0.2s;
    }
    
    .action-links a:hover { color: var(--color-primary); }
    .action-links a.text-danger:hover { color: #EF4444; }
</style>

<div class="page-header-action">
    <div>
        <h2 style="font-family: 'Manrope', sans-serif; font-size: 1.5rem; color: var(--color-ink);">Daftar Lowongan</h2>
        <p style="color: var(--color-ink-soft); font-size: 0.9rem; margin-top: 0.25rem;">Kelola semua pekerjaan yang telah Anda terbitkan.</p>
    </div>
    <a href="{{ route('pemberi.pekerjaan.create') }}" class="btn-primary">
        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Buat Lowongan
    </a>
</div>

<!-- Menampilkan pesan sukses/error dari session -->
@if (session('success'))
    <div style="background: #C6F6D5; border: 1px solid #48BB78; color: #2F855A; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">
        ✅ {{ session('success') }}
    </div>
@endif

<div class="table-card">
    <table class="modern-table">
        <thead>
            <tr>
                <th>Detail Pekerjaan</th>
                <th>Tanggal Pengerjaan</th>
                <th>Status</th>
                <th>Pelamar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pekerjaan as $p)
            <tr>
                <td>
                    <a href="{{ route('pemberi.pekerjaan.show', $p->id_pekerjaan) }}" class="job-title">{{ $p->nama_pekerjaan }}</a>
                    <div class="job-meta">Rp {{ number_format($p->upah, 0, ',', '.') }} • Butuh {{ $p->jumlah_pekerja }} orang</div>
                </td>
                <td>
                    <div style="font-weight: 500;">{{ \Carbon\Carbon::parse($p->tanggal_pengerjaan)->translatedFormat('d F Y') }}</div>
                </td>
                <td>
                    <span class="status-badge status-{{ strtolower($p->status_pekerjaan) }}">
                        {{ $p->status_pekerjaan }}
                    </span>
                </td>
                <td>
                    <div style="font-weight: 600;">{{ $p->lamaran_count }} <span style="color: var(--color-ink-soft); font-weight: 400; font-size: 0.85rem;">Total Pelamar</span></div>
                    <div style="font-size: 0.8rem; color: #C05621; margin-top: 4px;">{{ $p->menunggu_count }} Menunggu</div>
                </td>
                <td>
                    <div class="action-links">
                        <a href="{{ route('pemberi.pekerjaan.show', $p->id_pekerjaan) }}">Detail</a>
                        <a href="{{ route('pemberi.pelamar.index', $p->id_pekerjaan) }}">Lihat Pelamar</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 3rem 1rem; color: var(--color-ink-soft);">
                    <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="1.5" fill="none" style="margin-bottom: 1rem; opacity: 0.5;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <p style="font-size: 1rem; font-weight: 500;">Belum ada lowongan yang diterbitkan.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection