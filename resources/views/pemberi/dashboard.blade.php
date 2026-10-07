@extends('pemberi.layout')

@section('title', 'Beranda')

@section('content')
<style>
    /* Grid Utama: Kiri (70%) Kanan (30%) */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        align-items: start;
    }

    .left-col { display: flex; flex-direction: column; gap: 2rem; }

    /* --- HERO BANNER (Oranye/Coral) --- */
    .hero-banner {
        background: linear-gradient(135deg, var(--color-secondary) 0%, #E66A3B 100%);
        border-radius: 16px;
        padding: 2.5rem;
        color: white;
        box-shadow: 0 10px 25px rgba(255, 138, 91, 0.25);
    }

    .hero-banner h1 {
        font-family: 'Manrope', sans-serif;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .hero-banner p {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 1.5rem;
        max-width: 70%;
        line-height: 1.5;
    }

    .hero-btn {
        background-color: var(--color-accent);
        color: var(--color-ink);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-block;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .hero-btn:hover { transform: translateY(-2px); }

    /* --- SECTION HEADERS --- */
    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .section-head h3 {
        font-family: 'Manrope', sans-serif;
        font-size: 1.2rem;
        color: var(--color-ink);
    }

    .section-head a {
        font-size: 0.85rem;
        color: var(--color-primary-dark);
        font-weight: 600;
        text-decoration: none;
    }

    /* --- STAT CARDS --- */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--color-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .stat-num {
        font-family: 'Manrope', sans-serif;
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--color-ink);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--color-ink-soft);
        font-weight: 600;
    }

    /* --- TABEL MODERN --- */
    .table-container {
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
    }

    .modern-table tr:last-child td { border-bottom: none; }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #F0FFF4;
        color: #2F855A;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .status-badge.warning { background: #FFFAF0; color: #C05621; }

    /* --- KOLOM KANAN --- */
    .right-col { display: flex; flex-direction: column; gap: 2rem; }

    .new-applicants {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--color-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .applicant-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--color-border);
    }

    .applicant-item:last-child { border-bottom: none; padding-bottom: 0; }

    .app-avatar {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: #EBF8FF;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 0.95rem;
        color: var(--color-primary-dark);
    }

    .app-info h5 { font-size: 0.95rem; margin-bottom: 0.2rem; color: var(--color-ink); }
    .app-info p { font-size: 0.8rem; color: var(--color-ink-soft); }
</style>

<div class="dashboard-grid">
    
    <!-- === KOLOM KIRI === -->
    <div class="left-col">
        
        <!-- Banner -->
        <div class="hero-banner">
            <h1>Hai, {{ session('user_name', 'User') }}!</h1>
            <p>Ada 4 pelamar baru yang menunggu untuk ditinjau hari ini. Mari kita mulai proses seleksinya.</p>
            <a href="{{ route('pemberi.pekerjaan.create') }}" class="hero-btn">Buat Lowongan Baru</a>
        </div>

        <!-- Stat Cards -->
        <div>
            <div class="section-head">
                <h3>Ringkasan Lowongan</h3>
                <a href="{{ route('pemberi.pekerjaan.index') }}">Lihat semua</a>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div>
                        <div class="stat-num">{{ $perStatus['buka'] ?? 3 }}</div>
                        <div class="stat-label">Lowongan Aktif</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="stat-num">{{ $totalPelamar ?? 0 }}</div>
                        <div class="stat-label">Total Pelamar</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="stat-num">{{ $pelamarMenunggu ?? 0 }}</div>
                        <div class="stat-label">Menunggu Tinjauan</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="stat-num">{{ $perStatus['selesai'] ?? 1 }}</div>
                        <div class="stat-label">Pekerjaan Selesai</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Progress -->
        <div>
            <div class="section-head">
                <h3>Lowongan Terbaru</h3>
                <a href="{{ route('pemberi.pekerjaan.index') }}">Lihat semua</a>
            </div>
            <div class="table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Nama Pekerjaan</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-weight: 600;">Bantu Pindahan Rumah</td>
                            <td>Mulyorejo</td>
                            <td><span class="status-badge">Aktif</span></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Bersih-bersih Taman</td>
                            <td>Gubeng</td>
                            <td><span class="status-badge warning">Menunggu</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- === KOLOM KANAN === -->
    <div class="right-col">
        <div class="new-applicants">
            <div class="section-head">
                <h3>Pelamar Baru</h3>
                <a href="{{ route('pemberi.lamaran.index') }}">Semua</a>
            </div>
            
            <div class="applicant-item">
                <div class="app-avatar">A</div>
                <div class="app-info">
                    <h5>Andi Pratama</h5>
                    <p>Melamar: Bantu Pindahan</p>
                </div>
            </div>
            
            <div class="applicant-item">
                <div class="app-avatar">B</div>
                <div class="app-info">
                    <h5>Budi Santoso</h5>
                    <p>Melamar: Bersih-bersih Taman</p>
                </div>
            </div>
            
            <div class="applicant-item">
                <div class="app-avatar">S</div>
                <div class="app-info">
                    <h5>Siti Aisyah</h5>
                    <p>Melamar: Bantu Pindahan</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection