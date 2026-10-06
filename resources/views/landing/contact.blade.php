@extends('layout')

@section('title', 'Teman Kerja | Kontak Kami')

@section('styles')
<style>
    /* Memastikan halaman bisa di-scroll dan background full biru */
    body {
        overflow-y: auto !important; 
        background-color: var(--color-primary);
    }

    .contact-section {
        padding: 60px 20px 80px 20px;
    }

    .contact-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* HEADER */
    .contact-header {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 60px auto;
    }

    .contact-header h2 {
        color: #FFFFFF;
        font-size: 3.2rem;
        font-weight: 900; 
        margin-bottom: 10px;
        font-family: var(--font-heading);
    }

    .contact-header h3 {
        color: #FFFFFF;
        font-size: 1.5rem;
        font-weight: 900;
        margin-bottom: 20px;
        font-family: var(--font-heading);
    }

    .contact-header p {
        color: rgba(255, 255, 255, 0.88); 
        font-size: 1.05rem; 
        font-weight: normal;
        line-height: 1.6;
        max-width: 70ch; 
        margin: 0 auto;
    }

    /* GRID 3 KOTAK */
    .contact-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    /* KARTU KONTAK (Dibuat sebagai tag <a> agar seluruh kotak bisa diklik) */
    .contact-card {
        background-color: #FFFFFF;
        border: none;
        border-radius: 16px;
        padding: 40px 30px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        text-decoration: none; /* Menghilangkan garis bawah link */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Efek melayang saat kursor diarahkan ke kotak */
    .contact-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    }

    .card-icon {
        background-color: var(--color-bg); /* Lingkaran abu muda tipis di belakang ikon */
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
    }

    .contact-card h4 {
        color: #000000;
        font-size: 1.5rem;
        font-weight: 900;
        margin-bottom: 15px;
        font-family: var(--font-heading);
    }

    .contact-value {
        color: var(--color-primary-dark);
        font-size: 1.15rem;
        font-weight: 900;
        margin-bottom: 15px;
    }

    .contact-card p {
        color: #000000;
        font-size: 1.05rem; /* Sesuai dengan ukuran penjelasan di home & about */
        font-weight: normal;
        line-height: 1.6;
        margin: 0;
    }

    /* RESPONSIVE: Jadi 1 kolom bersusun di layar HP */
    @media (max-width: 900px) {
        .contact-grid { 
            grid-template-columns: 1fr; 
            max-width: 400px;
            margin: 0 auto;
        }
        .contact-header h2 { font-size: 2.2rem; }
    }
</style>
@endsection

@section('content')
<section class="contact-section">
    <div class="contact-container">
        
        <!-- HEADER -->
        <div class="contact-header">
            <h2>Kontak Kami</h2>
            <h3>Butuh bantuan? Kami siap membantu.</h3>
            <p>Punya pertanyaan, menemukan kendala, atau ingin menyampaikan masukan? Hubungi Teman Kerja melalui informasi kontak di bawah ini.</p>
        </div>

        <div class="contact-grid">
            
            <!-- KARTU 1: EMAIL -->
            <!-- Link mailto: akan otomatis membuka aplikasi Email bawaan -->
            <a href="mailto:temankerja@gmail.com" class="contact-card">
                <div class="card-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
                <h4>Email</h4>
                <div class="contact-value">temankerja@gmail.com</div>
                <p>Untuk pertanyaan, bantuan akun, atau laporan masalah.</p>
            </a>

            <!-- KARTU 2: ALAMAT -->
            <!-- Link akan otomatis mencari lokasi di Google Maps -->
            <a href="https://maps.google.com/?q=Fakultas+Teknologi+Maju+dan+Multidisiplin+Universitas+Airlangga+Surabaya" target="_blank" class="contact-card">
                <div class="card-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <h4>Alamat</h4>
                <div class="contact-value">FTMM Universitas Airlangga, Surabaya</div>
                <p>Kalau memang sistem ini dibuat untuk daerah tertentu, alamat kantor bisa dikunjungi di sini.</p>
            </a>

            <!-- KARTU 3: WHATSAPP / TELEPON -->
            <!-- Link wa.me akan otomatis membuka chat WhatsApp. Ubah 628... dengan nomor aslimu -->
            <a href="https://wa.me/6281234567890" target="_blank" class="contact-card">
                <div class="card-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </div>
                <h4>WhatsApp</h4>
                <div class="contact-value">08xx-xxxx-xxxx</div>
                <p>Untuk bantuan dan pertanyaan secara langsung dengan tim kami.</p>
            </a>

        </div>
    </div>
</section>
@endsection