<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KeahlianController as AdminKeahlianController;
use App\Http\Controllers\Admin\PemberiKerjaController as AdminPemberiKerjaController;
use App\Http\Controllers\Admin\PencariKerjaController as AdminPencariKerjaController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\VerifikasiKeahlianController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Pemberi\BuktiController as PemberiBuktiController;
use App\Http\Controllers\Pemberi\DashboardController as PemberiDashboardController;
use App\Http\Controllers\Pemberi\LamaranController as PemberiLamaranController;
use App\Http\Controllers\Pemberi\NotifikasiController as PemberiNotifikasiController;
use App\Http\Controllers\Pemberi\PekerjaanController as PemberiPekerjaanController;
use App\Http\Controllers\Pemberi\ProfilController as PemberiProfilController;
use App\Http\Controllers\Pemberi\RatingController as PemberiRatingController;
use App\Http\Controllers\Pencari\BuktiController as PencariBuktiController;
use App\Http\Controllers\Pencari\DashboardController as PencariDashboardController;
use App\Http\Controllers\Pencari\KeahlianController as PencariKeahlianController;
use App\Http\Controllers\Pencari\LamaranController as PencariLamaranController;
use App\Http\Controllers\Pencari\NotifikasiController as PencariNotifikasiController;
use App\Http\Controllers\Pencari\PekerjaanController as PencariPekerjaanController;
use App\Http\Controllers\Pencari\ProfilController as PencariProfilController;
use App\Http\Controllers\Pencari\RatingController as PencariRatingController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing.home');
Route::view('/about', 'landing.about');
Route::view('/contact', 'landing.contact');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1')->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.process');

Route::middleware('role:admin')->group(function (): void {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('admin', AdminController::class)->parameters(['admin' => 'admin:id_admin'])->except('show');
    Route::resource('pemberi_kerja', AdminPemberiKerjaController::class)
        ->parameters(['pemberi_kerja' => 'pemberi_kerja:id_pemberi'])->except('show');
    Route::resource('pencari_kerja', AdminPencariKerjaController::class)
        ->parameters(['pencari_kerja' => 'pencari_kerja:id_pencari'])->except('show');
    Route::resource('keahlian', AdminKeahlianController::class)
        ->parameters(['keahlian' => 'keahlian:id_keahlian'])->except('show');
    Route::get('/admin/verifikasi-keahlian', [VerifikasiKeahlianController::class, 'index'])->name('admin.verifikasi-keahlian');
    Route::patch('/admin/verifikasi-keahlian/{id_keahlian_pencari}', [VerifikasiKeahlianController::class, 'keputusan'])
        ->name('admin.verifikasi-keahlian.keputusan');
    Route::get('/admin/transaksi/{jenis}', [TransaksiController::class, 'index'])
        ->whereIn('jenis', ['pekerjaan', 'lamaran', 'bukti', 'rating', 'notifikasi'])->name('admin.transaksi.index');
    Route::get('/admin/akun/{role}/{id}/ktp', [DocumentController::class, 'ktp'])
        ->whereIn('role', ['pemberi_kerja', 'pencari_kerja'])->whereNumber('id')->name('admin.akun.ktp');
});

Route::middleware('role:pemberi_kerja')->prefix('pemberi')->name('pemberi.')->group(function (): void {
    Route::get('/dashboard', [PemberiDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [PemberiProfilController::class, 'show'])->name('profil.show');
    Route::get('/profil/edit', [PemberiProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [PemberiProfilController::class, 'update'])->name('profil.update');
    Route::get('/profil/foto', [PemberiProfilController::class, 'foto'])->name('profil.foto');
    Route::resource('pekerjaan', PemberiPekerjaanController::class)
        ->parameters(['pekerjaan' => 'pekerjaan:id_pekerjaan']);
    Route::patch('/pekerjaan/{pekerjaan:id_pekerjaan}/mulai', [PemberiPekerjaanController::class, 'mulai'])->name('pekerjaan.mulai');
    Route::patch('/pekerjaan/{pekerjaan:id_pekerjaan}/tutup', [PemberiPekerjaanController::class, 'tutup'])->name('pekerjaan.tutup');
    Route::get('/lamaran', [PemberiLamaranController::class, 'index'])->name('lamaran.index');
    Route::get('/pekerjaan/{pekerjaan:id_pekerjaan}/pelamar', [PemberiLamaranController::class, 'perPekerjaan'])->name('pelamar.index');
    Route::patch('/lamaran/{lamaran:id_lamaran}', [PemberiLamaranController::class, 'update'])->name('lamaran.update');
    Route::get('/pekerjaan/{pekerjaan:id_pekerjaan}/bukti', [PemberiBuktiController::class, 'create'])->name('bukti.create');
    Route::post('/pekerjaan/{pekerjaan:id_pekerjaan}/bukti', [PemberiBuktiController::class, 'store'])->name('bukti.store');
    Route::get('/bukti/{bukti:id_bukti}/{jenis}', [DocumentController::class, 'bukti'])
        ->whereIn('jenis', ['kerja', 'bayar'])->name('bukti.file');
    Route::get('/lamaran/{lamaran:id_lamaran}/rating', [PemberiRatingController::class, 'form'])->name('rating.form');
    Route::post('/lamaran/{lamaran:id_lamaran}/rating', [PemberiRatingController::class, 'store'])->name('rating.store');
    Route::put('/rating/{rating:id_rating}', [PemberiRatingController::class, 'update'])->name('rating.update');
    Route::get('/notifikasi', [PemberiNotifikasiController::class, 'index'])->name('notifikasi.index');
});

Route::middleware('role:pencari_kerja')->group(function (): void {
    Route::get('/pencari/dashboard', [PencariDashboardController::class, 'index'])->name('pencari.dashboard');
    Route::get('/pencari/profil', [PencariProfilController::class, 'show'])->name('pencari.profil');
    Route::put('/pencari/profil', [PencariProfilController::class, 'update'])->name('pencari.profil.update');
    Route::get('/keahlian_pencari_kerja', [PencariKeahlianController::class, 'index'])->name('keahlian_pencari_kerja.index');
    Route::get('/keahlian_pencari_kerja/create', [PencariKeahlianController::class, 'create'])->name('keahlian_pencari_kerja.create');
    Route::post('/keahlian_pencari_kerja', [PencariKeahlianController::class, 'store'])->name('keahlian_pencari_kerja.store');
    Route::get('/keahlian_pencari_kerja/{id_keahlian_pencari}/edit', [PencariKeahlianController::class, 'edit'])
        ->name('keahlian_pencari_kerja.edit');
    Route::put('/keahlian_pencari_kerja/{id_keahlian_pencari}', [PencariKeahlianController::class, 'update'])
        ->name('keahlian_pencari_kerja.update');
    Route::delete('/keahlian_pencari_kerja/{id_keahlian_pencari}', [PencariKeahlianController::class, 'destroy'])
        ->name('keahlian_pencari_kerja.destroy');
    Route::get('/pencari/cari-pekerjaan', [PencariPekerjaanController::class, 'index'])->name('pencari.cari-pekerjaan');
    Route::get('/pekerjaan/{pekerjaan:id_pekerjaan}', [PencariPekerjaanController::class, 'show'])->name('pekerjaan.show');
    Route::post('/pencari/pekerjaan/{pekerjaan:id_pekerjaan}/lamar', [PencariLamaranController::class, 'store'])->name('pencari.lamar');
    Route::get('/pencari/lamaran-saya', [PencariLamaranController::class, 'index'])->name('pencari.lamaran-saya');
    Route::delete('/pencari/lamaran/{lamaran:id_lamaran}', [PencariLamaranController::class, 'batalkan'])->name('pencari.lamaran.batal');
    Route::get('/pencari/lamaran/{lamaran:id_lamaran}/bukti', [PencariBuktiController::class, 'create'])->name('pencari.bukti.create');
    Route::post('/pencari/lamaran/{lamaran:id_lamaran}/bukti', [PencariBuktiController::class, 'store'])->name('pencari.bukti.store');
    Route::get('/pencari/lamaran/{lamaran:id_lamaran}/rating', [PencariRatingController::class, 'form'])->name('pencari.rating.form');
    Route::post('/pencari/lamaran/{lamaran:id_lamaran}/rating', [PencariRatingController::class, 'store'])->name('pencari.rating.store');
    Route::put('/pencari/rating/{rating:id_rating}', [PencariRatingController::class, 'update'])->name('pencari.rating.update');
    Route::get('/pencari/notifikasi', [PencariNotifikasiController::class, 'index'])->name('pencari.notifikasi');
});

<<<<<<< HEAD

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
=======
Route::middleware('role:admin,pemberi_kerja,pencari_kerja')->group(function (): void {
    Route::get('/dokumen/bukti/{bukti:id_bukti}/{jenis}', [DocumentController::class, 'bukti'])
        ->whereIn('jenis', ['kerja', 'bayar'])->name('dokumen.bukti');
    Route::get('/dokumen/keahlian/{pengajuan:id_keahlian_pencari}', [DocumentController::class, 'keahlian'])->name('dokumen.keahlian');
>>>>>>> origin/naura-controller
});
