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

Route::get('/admin/dashboard', function () {

    if (session('role') !== 'admin') {
        return redirect()->route('login');
    }

    return view('dashboard.admin');

})->name('admin.dashboard');


/*
|--------------------------------------------------------------------------
| DASHBOARD PEMBERI KERJA
|--------------------------------------------------------------------------
*/

Route::get('/pemberi/dashboard', function () {

    if (session('role') !== 'pemberi_kerja') {
        return redirect()->route('login');
    }

    return view('dashboard.pemberi');

})->name('pemberi.dashboard');


/*
|--------------------------------------------------------------------------
| DASHBOARD PENCARI KERJA
|--------------------------------------------------------------------------
*/

Route::get('/pencari/dashboard', function () {

    if (session('role') !== 'pencari_kerja') {
        return redirect()->route('login');
    }

    return view('dashboard.pencari');

})->name('pencari.dashboard');


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
| CRUD PEKERJAAN
|--------------------------------------------------------------------------
*/

Route::resource('pekerjaan', PekerjaanController::class)
    ->parameters([
        'pekerjaan' => 'pekerjaan:id_pekerjaan'
    ]);


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


/*
|--------------------------------------------------------------------------
| CRUD NOTIFIKASI
|--------------------------------------------------------------------------
*/

Route::resource('notifikasi', NotifikasiController::class)
    ->parameters([
        'notifikasi' => 'notifikasi:id_notifikasi'
    ]);