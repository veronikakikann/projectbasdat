<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

// ================================
// ADMIN
// ================================
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PemberiKerjaController as AdminPemberiKerjaController;
use App\Http\Controllers\Admin\PencariKerjaController as AdminPencariKerjaController;
use App\Http\Controllers\Admin\KeahlianController as AdminKeahlianController;
use App\Http\Controllers\Admin\VerifikasiKeahlianController;

// ================================
// PEMBERI
// ================================
use App\Http\Controllers\Pemberi\DashboardController as PemberiDashboardController;
use App\Http\Controllers\Pemberi\ProfilController as PemberiProfilController;
use App\Http\Controllers\Pemberi\PekerjaanController as PemberiPekerjaanController;
use App\Http\Controllers\Pemberi\LamaranController as PemberiLamaranController;
use App\Http\Controllers\Pemberi\BuktiController as PemberiBuktiController;
use App\Http\Controllers\Pemberi\RatingController as PemberiRatingController;
use App\Http\Controllers\Pemberi\NotifikasiController as PemberiNotifikasiController;

// ================================
// PENCARI
// ================================
use App\Http\Controllers\Pencari\DashboardController as PencariDashboardController;
use App\Http\Controllers\Pencari\ProfilController as PencariProfilController;
use App\Http\Controllers\Pencari\KeahlianController as PencariKeahlianController;
use App\Http\Controllers\Pencari\PekerjaanController as PencariPekerjaanController;
use App\Http\Controllers\Pencari\LamaranController as PencariLamaranController;
use App\Http\Controllers\Pencari\BuktiController as PencariBuktiController;
use App\Http\Controllers\Pencari\RatingController as PencariRatingController;
use App\Http\Controllers\Pencari\NotifikasiController as PencariNotifikasiController;


/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing.home');
});

Route::get('/about', function () {
    return view('landing.about');
});

Route::get('/contact', function () {
    return view('landing.contact');
});


/*
|--------------------------------------------------------------------------
| LOGIN & REGISTER
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

Route::get('/register', [RegisterController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'register'])
    ->name('register.process');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware('role:admin')->group(function () {

    // Dashboard
    Route::get(
        '/admin/dashboard',
        [AdminDashboardController::class, 'index']
    )->name('admin.dashboard');


    // CRUD Admin
    Route::resource(
        'admin',
        AdminController::class
    )->parameters([
        'admin' => 'admin:id_admin'
    ])->except('show');


    // CRUD Pemberi Kerja
    Route::resource(
        'pemberi_kerja',
        AdminPemberiKerjaController::class
    )->parameters([
        'pemberi_kerja' => 'pemberi_kerja:id_pemberi'
    ])->except('show');


    // CRUD Pencari Kerja
    Route::resource(
        'pencari_kerja',
        AdminPencariKerjaController::class
    )->parameters([
        'pencari_kerja' => 'pencari_kerja:id_pencari'
    ])->except('show');


    // Master Keahlian
    Route::resource(
        'keahlian',
        AdminKeahlianController::class
    )->parameters([
        'keahlian' => 'keahlian:id_keahlian'
    ])->except('show');


    // Verifikasi keahlian pencari
    Route::get(
        '/admin/verifikasi-keahlian',
        [VerifikasiKeahlianController::class, 'index']
    )->name('admin.verifikasi-keahlian');

    Route::patch(
        '/admin/verifikasi-keahlian/{id_keahlian_pencari}',
        [VerifikasiKeahlianController::class, 'keputusan']
    )->name('admin.verifikasi-keahlian.keputusan');
});


/*
|--------------------------------------------------------------------------
| PEMBERI KERJA
|--------------------------------------------------------------------------
*/

Route::middleware('role:pemberi_kerja')
    ->prefix('pemberi')
    ->name('pemberi.')
    ->group(function () {

        // Dashboard
        Route::get(
            '/dashboard',
            [PemberiDashboardController::class, 'index']
        )->name('dashboard');


        // Profil
        Route::get(
            '/profil',
            [PemberiProfilController::class, 'show']
        )->name('profil.show');

        Route::get(
            '/profil/edit',
            [PemberiProfilController::class, 'edit']
        )->name('profil.edit');

        Route::put(
            '/profil',
            [PemberiProfilController::class, 'update']
        )->name('profil.update');

        Route::get(
            '/profil/foto',
            [PemberiProfilController::class, 'foto']
        )->name('profil.foto');


        // Pekerjaan
        Route::resource(
            'pekerjaan',
            PemberiPekerjaanController::class
        )->parameters([
            'pekerjaan' => 'pekerjaan:id_pekerjaan'
        ]);

        Route::patch(
            '/pekerjaan/{pekerjaan:id_pekerjaan}/mulai',
            [PemberiPekerjaanController::class, 'mulai']
        )->name('pekerjaan.mulai');

        Route::patch(
            '/pekerjaan/{pekerjaan:id_pekerjaan}/tutup',
            [PemberiPekerjaanController::class, 'tutup']
        )->name('pekerjaan.tutup');


        // Lamaran / Pelamar
        Route::get(
            '/lamaran',
            [PemberiLamaranController::class, 'index']
        )->name('lamaran.index');

        Route::get(
            '/pekerjaan/{pekerjaan:id_pekerjaan}/pelamar',
            [PemberiLamaranController::class, 'perPekerjaan']
        )->name('pelamar.index');

        Route::patch(
            '/lamaran/{lamaran:id_lamaran}',
            [PemberiLamaranController::class, 'update']
        )->name('lamaran.update');


        // Bukti penyelesaian
        Route::get(
            '/pekerjaan/{pekerjaan:id_pekerjaan}/bukti',
            [PemberiBuktiController::class, 'create']
        )->name('bukti.create');

        Route::post(
            '/pekerjaan/{pekerjaan:id_pekerjaan}/bukti',
            [PemberiBuktiController::class, 'store']
        )->name('bukti.store');

        Route::get(
            '/pekerjaan/{pekerjaan:id_pekerjaan}/bukti/{jenis}',
            [PemberiBuktiController::class, 'file']
        )
        ->whereIn('jenis', ['kerja', 'bayar'])
        ->name('bukti.file');


        // Rating
        Route::get(
            '/lamaran/{lamaran:id_lamaran}/rating',
            [PemberiRatingController::class, 'form']
        )->name('rating.form');

        Route::post(
            '/lamaran/{lamaran:id_lamaran}/rating',
            [PemberiRatingController::class, 'store']
        )->name('rating.store');

        Route::put(
            '/rating/{rating:id_rating}',
            [PemberiRatingController::class, 'update']
        )->name('rating.update');


        // Notifikasi
        Route::get(
            '/notifikasi',
            [PemberiNotifikasiController::class, 'index']
        )->name('notifikasi.index');
});


/*
|--------------------------------------------------------------------------
| PENCARI KERJA
|--------------------------------------------------------------------------
*/

Route::middleware('role:pencari_kerja')->group(function () {

    // Dashboard
    Route::get(
        '/pencari/dashboard',
        [PencariDashboardController::class, 'index']
    )->name('pencari.dashboard');


    // Profil
    Route::get(
        '/pencari/profil',
        [PencariProfilController::class, 'show']
    )->name('pencari.profil');

    Route::put(
        '/pencari/profil',
        [PencariProfilController::class, 'update']
    )->name('pencari.profil.update');


    // Keahlian pencari
    Route::get(
        '/keahlian_pencari_kerja',
        [PencariKeahlianController::class, 'index']
    )->name('keahlian_pencari_kerja.index');

    Route::get(
        '/keahlian_pencari_kerja/create',
        [PencariKeahlianController::class, 'create']
    )->name('keahlian_pencari_kerja.create');

    Route::post(
        '/keahlian_pencari_kerja',
        [PencariKeahlianController::class, 'store']
    )->name('keahlian_pencari_kerja.store');

    Route::get(
        '/keahlian_pencari_kerja/{id_keahlian_pencari}/edit',
        [PencariKeahlianController::class, 'edit']
    )->name('keahlian_pencari_kerja.edit');

    Route::put(
        '/keahlian_pencari_kerja/{id_keahlian_pencari}',
        [PencariKeahlianController::class, 'update']
    )->name('keahlian_pencari_kerja.update');

    Route::delete(
        '/keahlian_pencari_kerja/{id_keahlian_pencari}',
        [PencariKeahlianController::class, 'destroy']
    )->name('keahlian_pencari_kerja.destroy');


    // Cari pekerjaan
    Route::get(
        '/pencari/cari-pekerjaan',
        [PencariPekerjaanController::class, 'index']
    )->name('pencari.cari-pekerjaan');


    // Detail pekerjaan
    Route::get(
        '/pekerjaan/{pekerjaan}',
        [PencariPekerjaanController::class, 'show']
    )->name('pekerjaan.show');


    // Lamar pekerjaan
    Route::post(
        '/pencari/pekerjaan/{pekerjaan}/lamar',
        [PencariLamaranController::class, 'store']
    )->name('pencari.lamar');


    // Lamaran saya
    Route::get(
        '/pencari/lamaran-saya',
        [PencariLamaranController::class, 'index']
    )->name('pencari.lamaran-saya');

    // Batalkan lamaran yang masih menunggu
    Route::delete(
        '/pencari/lamaran/{lamaran:id_lamaran}',
        [PencariLamaranController::class, 'batalkan']
    )->name('pencari.lamaran.batal');


    // Bukti penyelesaian
    Route::get(
        '/pencari/lamaran/{lamaran:id_lamaran}/bukti',
        [PencariBuktiController::class, 'create']
    )->name('pencari.bukti.create');

    Route::post(
        '/pencari/lamaran/{lamaran:id_lamaran}/bukti',
        [PencariBuktiController::class, 'store']
    )->name('pencari.bukti.store');


    // Rating
    Route::get(
        '/pencari/lamaran/{lamaran:id_lamaran}/rating',
        [PencariRatingController::class, 'form']
    )->name('pencari.rating.form');

    Route::post(
        '/pencari/lamaran/{lamaran:id_lamaran}/rating',
        [PencariRatingController::class, 'store']
    )->name('pencari.rating.store');

    Route::put(
        '/pencari/rating/{rating:id_rating}',
        [PencariRatingController::class, 'update']
    )->name('pencari.rating.update');

    // Notifikasi
    Route::get(
        '/pencari/notifikasi',
        [PencariNotifikasiController::class, 'index']
    )->name('pencari.notifikasi');
});