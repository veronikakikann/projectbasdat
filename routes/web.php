<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PemberiKerjaController;
use App\Http\Controllers\PencariKerjaController;
use App\Http\Controllers\KeahlianController;
use App\Http\Controllers\KeahlianPencariKerjaController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\LamaranController;
use App\Http\Controllers\BuktiPenyelesaianController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\NotifikasiController;

use App\Http\Controllers\Pemberi\PekerjaanController as PemberiPekerjaanController;
use App\Http\Controllers\Pemberi\LamaranController as PemberiLamaranController;
use App\Http\Controllers\Pemberi\NotifikasiController as PemberiNotifikasiController;
use App\Http\Controllers\Pemberi\DashboardController as PemberiDashboardController;
use App\Http\Controllers\Pemberi\BuktiController as PemberiBuktiController;
use App\Http\Controllers\Pemberi\RatingController as PemberiRatingController;
use App\Http\Controllers\Pemberi\ProfilController as PemberiProfilController;


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
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| REGISTER
|--------------------------------------------------------------------------
*/

Route::get('/register', [RegisterController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'register'])
    ->name('register.process');


/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN
|--------------------------------------------------------------------------
*/

Route::get(
    '/admin/dashboard',
    [AdminController::class, 'dashboard']
)->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| DASHBOARD PENCARI KERJA
|--------------------------------------------------------------------------
*/

Route::get(
    '/pencari/dashboard',
    [PencariKerjaController::class, 'dashboard']
)->middleware('role:pencari_kerja')->name('pencari.dashboard');


/*
|--------------------------------------------------------------------------
| PROFIL PENCARI KERJA
|--------------------------------------------------------------------------
*/

Route::get(
    '/pencari/profil',
    [PencariKerjaController::class, 'profil']
)->middleware('role:pencari_kerja')->name('pencari.profil');

Route::put(
    '/pencari/profil',
    [PencariKerjaController::class, 'updateProfil']
)->middleware('role:pencari_kerja')->name('pencari.profil.update');


/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/

Route::middleware('role:admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CRUD ADMIN
    |--------------------------------------------------------------------------
    */

    Route::resource('admin', AdminController::class)
        ->parameters([
            'admin' => 'admin:id_admin'
        ]);


    /*
    |--------------------------------------------------------------------------
    | CRUD PEMBERI KERJA
    |--------------------------------------------------------------------------
    */

    Route::resource('pemberi_kerja', PemberiKerjaController::class)
        ->parameters([
            'pemberi_kerja' => 'pemberi_kerja:id_pemberi'
        ]);


    /*
    |--------------------------------------------------------------------------
    | CRUD PENCARI KERJA
    |--------------------------------------------------------------------------
    */

    Route::resource('pencari_kerja', PencariKerjaController::class)
        ->parameters([
            'pencari_kerja' => 'pencari_kerja:id_pencari'
        ]);


    /*
    |--------------------------------------------------------------------------
    | CRUD KEAHLIAN
    |--------------------------------------------------------------------------
    */

    Route::resource('keahlian', KeahlianController::class)
        ->parameters([
            'keahlian' => 'keahlian:id_keahlian'
        ]);
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
            'dashboard',
            [PemberiDashboardController::class, 'index']
        )->name('dashboard');


        // Pekerjaan
        Route::resource(
            'pekerjaan',
            PemberiPekerjaanController::class
        )->parameters([
            'pekerjaan' => 'pekerjaan:id_pekerjaan'
        ]);

        Route::patch(
            'pekerjaan/{pekerjaan:id_pekerjaan}/mulai',
            [PemberiPekerjaanController::class, 'mulai']
        )->name('pekerjaan.mulai');

        Route::patch(
            'pekerjaan/{pekerjaan:id_pekerjaan}/tutup',
            [PemberiPekerjaanController::class, 'tutup']
        )->name('pekerjaan.tutup');


        // Pelamar
        Route::get(
            'pekerjaan/{pekerjaan:id_pekerjaan}/pelamar',
            [PemberiLamaranController::class, 'perPekerjaan']
        )->name('pelamar.index');

        Route::get(
            'lamaran',
            [PemberiLamaranController::class, 'index']
        )->name('lamaran.index');

        Route::patch(
            'lamaran/{lamaran:id_lamaran}',
            [PemberiLamaranController::class, 'update']
        )->name('lamaran.update');


        // Bukti penyelesaian
        Route::get(
            'pekerjaan/{pekerjaan:id_pekerjaan}/bukti',
            [PemberiBuktiController::class, 'create']
        )->name('bukti.create');

        Route::post(
            'pekerjaan/{pekerjaan:id_pekerjaan}/bukti',
            [PemberiBuktiController::class, 'store']
        )->name('bukti.store');

        Route::get(
            'pekerjaan/{pekerjaan:id_pekerjaan}/bukti/{jenis}',
            [PemberiBuktiController::class, 'file']
        )->whereIn('jenis', ['kerja', 'bayar'])
        ->name('bukti.file');


        // Rating
        Route::get(
            'lamaran/{lamaran:id_lamaran}/rating',
            [PemberiRatingController::class, 'form']
        )->name('rating.form');

        Route::post(
            'lamaran/{lamaran:id_lamaran}/rating',
            [PemberiRatingController::class, 'store']
        )->name('rating.store');

        Route::put(
            'rating/{rating:id_rating}',
            [PemberiRatingController::class, 'update']
        )->name('rating.update');


        // Notifikasi
        Route::get(
            'notifikasi',
            [PemberiNotifikasiController::class, 'index']
        )->name('notifikasi.index');


        // Profil
        Route::get(
            'profil',
            [PemberiProfilController::class, 'show']
        )->name('profil.show');

        Route::get(
            'profil/edit',
            [PemberiProfilController::class, 'edit']
        )->name('profil.edit');

        Route::put(
            'profil',
            [PemberiProfilController::class, 'update']
        )->name('profil.update');

        Route::get(
            'profil/foto',
            [PemberiProfilController::class, 'foto']
        )->name('profil.foto');
    });


/*
|--------------------------------------------------------------------------
| PENCARI KERJA
|--------------------------------------------------------------------------
*/

Route::middleware('role:pencari_kerja')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | KEAHLIAN PENCARI KERJA
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/keahlian_pencari_kerja',
        [KeahlianPencariKerjaController::class, 'index']
    )->name('keahlian_pencari_kerja.index');

    Route::get(
        '/keahlian_pencari_kerja/create',
        [KeahlianPencariKerjaController::class, 'create']
    )->name('keahlian_pencari_kerja.create');

    Route::post(
        '/keahlian_pencari_kerja',
        [KeahlianPencariKerjaController::class, 'store']
    )->name('keahlian_pencari_kerja.store');

    Route::get(
        '/keahlian_pencari_kerja/{id_pencari}/{id_keahlian}/edit',
        [KeahlianPencariKerjaController::class, 'edit']
    )->name('keahlian_pencari_kerja.edit');

    Route::put(
        '/keahlian_pencari_kerja/{id_pencari}/{id_keahlian}',
        [KeahlianPencariKerjaController::class, 'update']
    )->name('keahlian_pencari_kerja.update');

    Route::delete(
        '/keahlian_pencari_kerja/{id_pencari}/{id_keahlian}',
        [KeahlianPencariKerjaController::class, 'destroy']
    )->name('keahlian_pencari_kerja.destroy');


    /*
    |--------------------------------------------------------------------------
    | CARI PEKERJAAN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/pencari/cari-pekerjaan',
        [PekerjaanController::class, 'cari']
    )->name('pencari.cari-pekerjaan');


    /*
    |--------------------------------------------------------------------------
    | LAMAR PEKERJAAN
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/pencari/pekerjaan/{pekerjaan}/lamar',
        [LamaranController::class, 'lamar']
    )->name('pencari.lamar');


    /*
    |--------------------------------------------------------------------------
    | LAMARAN SAYA
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/pencari/lamaran-saya',
        [LamaranController::class, 'lamaranSaya']
    )->name('pencari.lamaran-saya');


    /*
    |--------------------------------------------------------------------------
    | DETAIL PEKERJAAN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/pekerjaan/{pekerjaan}',
        [PekerjaanController::class, 'show']
    )->name('pekerjaan.show');

    /*
    |--------------------------------------------------------------------------
    | CRUD LAMARAN
    |--------------------------------------------------------------------------
    */

    Route::resource('lamaran', LamaranController::class)
        ->parameters([
            'lamaran' => 'lamaran:id_lamaran'
        ]);


    /*
    |--------------------------------------------------------------------------
    | CRUD NOTIFIKASI
    |--------------------------------------------------------------------------
    */

    Route::resource('notifikasi', NotifikasiController::class)
        ->parameters([
            'notifikasi' => 'notifikasi:id_notifikasi'
        ]);


    /*
    |--------------------------------------------------------------------------
    | CRUD BUKTI PENYELESAIAN
    |--------------------------------------------------------------------------
    */

    Route::resource('bukti_penyelesaian', BuktiPenyelesaianController::class)
        ->parameters([
            'bukti_penyelesaian' => 'bukti_penyelesaian:id_bukti'
        ]);


    /*
    |--------------------------------------------------------------------------
    | CRUD RATING
    |--------------------------------------------------------------------------
    */

    Route::resource('rating', RatingController::class)
        ->parameters([
            'rating' => 'rating:id_rating'
        ]);
});