@extends('layout')

@section('title', 'Teman Kerja | Tentang Kami')

@section('styles')
<style>
    /* Memastikan halaman bisa di-scroll dan background full biru */
    body {
        overflow-y: auto !important; 
        background-color: var(--color-primary);
    }

    .about-section {
        padding: 60px 20px 80px 20px;
    }

    .about-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .about-header {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 60px auto;
    }

    .about-header h2 {
        color: #FFFFFF;
        font-size: 3.2rem;
        font-weight: 900; 
        margin-bottom: 20px;
        font-family: var(--font-heading);
    }

    /* DI-UPDATE: Ukuran disamakan dengan paragraf Home (1.05rem) dan transparansi disesuaikan */
    .about-header p {
        color: rgba(255, 255, 255, 0.88); 
        font-size: 1.05rem; 
        font-weight: normal;
        line-height: 1.6;
        max-width: 70ch; /* Agar lebarnya proporsional, tidak terlalu melebar */
        margin: 0 auto;
    }

    .role-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 50px;
    }

    /* Kotak Putih */
    .role-card {
        background-color: #FFFFFF;
        border: none;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        display: flex;
        flex-direction: column; 
    }

    .role-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .role-icon {
        display: flex;
        align-items: center;
    }

    .role-header h3 {
        color: #000000;
        font-size: 2rem;
        font-weight: 900;
        margin: 0;
        font-family: var(--font-heading);
    }

    /* DI-UPDATE: Ukuran teks dalam kotak juga disesuaikan ke 1.05rem agar seragam */
    .role-desc {
        color: #000000;
        font-size: 1.05rem;
        font-weight: normal; 
        margin-bottom: 30px;
        line-height: 1.6;
        text-align: justify; 
        flex-grow: 1; 
    }

    .step-title {
        margin-bottom: 20px;
        color: #000000;
        font-weight: 900;
        font-size: 1.25rem;
    }

    .step-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    /* DI-UPDATE: Font size diturunkan sedikit (0.95rem) agar list tidak terlihat terlalu padat */
    .step-list li {
        margin-bottom: 18px;
        display: flex;
        align-items: flex-start;
        line-height: 1.5;
        color: #000000;
        font-weight: normal; 
        font-size: 0.95rem;
    }

    .step-list li strong {
        font-weight: 900;
        font-size: 1rem;
    }

    .step-num {
        background-color: var(--color-accent);
        color: #000000;
        font-weight: 900;
        padding: 4px 10px;
        border-radius: 6px;
        margin-right: 15px;
        font-size: 0.95rem;
        min-width: 32px;
        text-align: center;
    }

    /* Banner Bawah Pink */
    .verification-banner {
        background-color: var(--color-secondary);
        color: #000000;
        text-align: center;
        padding: 40px 30px;
        border-radius: 16px;
    }

    .verification-banner h3 {
        margin-bottom: 15px;
        font-size: 2rem;
        font-weight: 900;
        font-family: var(--font-heading);
    }

    /* DI-UPDATE: Ukuran teks banner disesuaikan jadi 1.05rem */
    .verification-banner p {
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.6;
        font-size: 1.05rem;
        font-weight: normal;
    }

    @media (max-width: 768px) {
        .role-grid { grid-template-columns: 1fr; }
        .about-header h2 { font-size: 2.2rem; }
    }
</style>
@endsection

@section('content')
<section class="about-section">
    <div class="about-container">
        
        <!-- HEADER -->
        <div class="about-header">
            <h2>Tentang Teman Kerja</h2>
            <p>Teman Kerja hadir untuk mempertemukan orang yang membutuhkan pekerjaan dengan orang yang membutuhkan bantuan. Kami menyediakan ruang bagi pencari kerja dan pemberi kerja untuk saling menemukan berdasarkan kebutuhan, keahlian, dan lokasi.</p>
        </div>

        <div class="role-grid">
            
            <!-- KARTU PENCARI KERJA -->
            <div class="role-card">
                <div class="role-header">
                    <div class="role-icon">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <h3>Pencari Kerja</h3>
                </div>
                
                <p class="role-desc">Punya keahlian dan sedang mencari pekerjaan? Teman Kerja membantu kamu menemukan pekerjaan harian dan lepas yang sesuai dengan kemampuan dan lokasi kamu.</p>
                
                <div class="step-title">Cara Kerja</div>
                <ul class="step-list">
                    <li><span class="step-num">1</span> <div><strong>Buat Akun:</strong> Daftarkan diri dan lengkapi informasi yang diperlukan.</div></li>
                    <li><span class="step-num">2</span> <div><strong>Verifikasi:</strong> Lakukan verifikasi untuk memastikan akunmu terdaftar dan membantu menjaga keamanan.</div></li>
                    <li><span class="step-num">3</span> <div><strong>Temukan Pekerjaan:</strong> Cari pekerjaan berdasarkan jenis, lokasi, dan waktu.</div></li>
                    <li><span class="step-num">4</span> <div><strong>Ajukan Diri:</strong> Temukan pekerjaan yang sesuai dan ajukan dirimu kepada pemberi kerja.</div></li>
                    <li><span class="step-num">5</span> <div><strong>Mulai Bekerja:</strong> Setelah disepakati oleh kedua pihak, kamu dapat mulai bekerja.</div></li>
                </ul>
            </div>

            <!-- KARTU PEMBERI KERJA -->
            <div class="role-card">
                <div class="role-header">
                    <div class="role-icon">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21h18"></path>
                            <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"></path>
                            <path d="M9 21v-4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4"></path>
                        </svg>
                    </div>
                    <h3>Pemberi Kerja</h3>
                </div>
                
                <p class="role-desc">Butuh bantuan untuk pekerjaan harian? Teman Kerja membantu kamu menemukan pekerja yang sesuai dengan kebutuhan pekerjaanmu, baik untuk pekerjaan sederhana maupun keahlian tertentu.</p>
                
                <div class="step-title">Cara Kerja</div>
                <ul class="step-list">
                    <li><span class="step-num">1</span> <div><strong>Buat Akun:</strong> Daftarkan diri sebagai pemberi kerja dan lengkapi informasi.</div></li>
                    <li><span class="step-num">2</span> <div><strong>Verifikasi:</strong> Verifikasi akun untuk membantu menciptakan lingkungan kerja yang terpercaya.</div></li>
                    <li><span class="step-num">3</span> <div><strong>Buat Lowongan:</strong> Jelaskan pekerjaan yang dibutuhkan, lokasi, upah, dan persyaratan.</div></li>
                    <li><span class="step-num">4</span> <div><strong>Temukan Pekerja:</strong> Lihat pencari kerja yang tertarik dan sesuai kebutuhan.</div></li>
                    <li><span class="step-num">5</span> <div><strong>Tentukan Pekerja:</strong> Hubungi dan sepakati detail pekerjaan dengan pekerja yang dipilih.</div></li>
                </ul>
            </div>
        </div>

        <!-- BANNER BAWAH -->
        <div class="verification-banner">
            <h3>Mengapa harus terverifikasi?</h3>
            <p>Keamanan dan kepercayaan menjadi bagian penting dalam Teman Kerja. Karena itu, setiap pengguna perlu memiliki akun dan melalui proses verifikasi sebelum menggunakan layanan. Verifikasi membantu memastikan bahwa pengguna yang berinteraksi di platform merupakan pengguna yang terdaftar.</p>
        </div>

    </div>
</section>
@endsection