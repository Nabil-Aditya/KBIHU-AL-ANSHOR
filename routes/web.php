<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\JamaahController;
use App\Http\Controllers\Admin\DoaController;
use App\Http\Controllers\DoaPublicController;
use App\Http\Controllers\Admin\FotoController;
use App\Http\Controllers\FotoPublicController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\VideoPublicController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\BeritaPublicController;
use App\Http\Controllers\BeritaAllController;
use App\Http\Controllers\Admin\InfografisController;
use App\Http\Controllers\InfografisPublicController;

// Public Routes - Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Video Public Routes
Route::get('/video', [VideoPublicController::class, 'index'])->name('video.index');
Route::get('/video/kategori/{kategori}', [VideoPublicController::class, 'category'])->name('video.kategori');
Route::get('/video/{id}', [VideoPublicController::class, 'show'])->name('video.show');
Route::get('/api/video/{id}/embed', [VideoPublicController::class, 'getEmbedUrl'])->name('video.embed');

// Berita Public Routes
Route::get('/berita', [BeritaPublicController::class, 'index'])->name('berita.index');
Route::get('/berita/kategori/{kategori}', [BeritaPublicController::class, 'category'])->name('berita.kategori');
Route::get('/berita/{slug}', [BeritaPublicController::class, 'show'])->name('berita.show');

// Berita All Routes (LIFO - Semua Berita)
Route::get('/beritaa', [BeritaAllController::class, 'index'])->name('berita.all');
Route::get('/berit/kategori/{kategori}', [BeritaAllController::class, 'category'])->name('berita.all.category');
Route::get('/api/berita/latest/{limit?}', [BeritaAllController::class, 'latest'])->name('berita.latest.api');

// Foto Public Routes
Route::get('/foto', [FotoPublicController::class, 'index'])->name('foto.index');
Route::get('/foto/kategori/{kategori}', [FotoPublicController::class, 'category'])->name('foto.kategori');
Route::get('/foto/{id}', [FotoPublicController::class, 'show'])->name('foto.show');
Route::get('/api/foto/{id}', [FotoPublicController::class, 'getFotoData'])->name('foto.data');

// Infografis Public Routes
Route::get('/infografis', [InfografisPublicController::class, 'index'])->name('infografis.index');
Route::get('/infografis/{slug}', [InfografisPublicController::class, 'show'])->name('infografis.show');
Route::get('/api/infografis/{id}', [InfografisPublicController::class, 'getInfografisData'])->name('infografis.data');

// Doa Public Routes
Route::get('/doa', [DoaPublicController::class, 'index'])->name('doa.public.index');
Route::get('/doa/view/{id}', [DoaPublicController::class, 'view'])->name('doa.public.view');
Route::get('/doa/kategori/{kategori}', [DoaPublicController::class, 'category'])->name('doa.kategori');
Route::get('/api/doa/{id}', [DoaPublicController::class, 'show'])->name('doa.show');

// Other public pages
Route::get('/blog/{id}', function ($id) {
    return view('blog-details', ['id' => $id]);
})->name('blog.details');

Route::get('/portfolio/{id}', function ($id) {
    return view('portfolio-details', ['id' => $id]);
})->name('portfolio.details');

Route::get('/service/{id}', function ($id) {
    return view('service-details', ['id' => $id]);
})->name('service.details');

Route::get('/starter', function () {
    return view('starter-page');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/internal/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/internal/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/internal/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Berita Management
    Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
    Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}', [BeritaController::class, 'show'])->where('id', '[0-9]+')->name('berita.show');
    Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])->where('id', '[0-9]+')->name('berita.edit');
    Route::put('/berita/{id}', [BeritaController::class, 'update'])->where('id', '[0-9]+')->name('berita.update');
    Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->where('id', '[0-9]+')->name('berita.destroy');

    // Video Management
    Route::get('/video', [VideoController::class, 'index'])->name('video.index');
    Route::post('/video', [VideoController::class, 'store'])->name('video.store');
    Route::get('/video/{id}', [VideoController::class, 'show'])->where('id', '[0-9]+')->name('video.show');
    Route::get('/video/{id}/edit', [VideoController::class, 'edit'])->where('id', '[0-9]+')->name('video.edit');
    Route::put('/video/{id}', [VideoController::class, 'update'])->where('id', '[0-9]+')->name('video.update');
    Route::delete('/video/{id}', [VideoController::class, 'destroy'])->where('id', '[0-9]+')->name('video.destroy');

    // Foto Management
    Route::get('/foto', [FotoController::class, 'index'])->name('foto.index');
    Route::post('/foto', [FotoController::class, 'store'])->name('foto.store');
    Route::get('/foto/{id}', [FotoController::class, 'show'])->where('id', '[0-9]+')->name('foto.show');
    Route::get('/foto/{id}/edit', [FotoController::class, 'edit'])->where('id', '[0-9]+')->name('foto.edit');
    Route::put('/foto/{id}', [FotoController::class, 'update'])->where('id', '[0-9]+')->name('foto.update');
    Route::delete('/foto/{id}', [FotoController::class, 'destroy'])->where('id', '[0-9]+')->name('foto.destroy');

    // Infografis Management
    Route::get('/infografis', [InfografisController::class, 'index'])->name('infografis.index');
    Route::post('/infografis', [InfografisController::class, 'store'])->name('infografis.store');
    Route::get('/infografis/{id}', [InfografisController::class, 'show'])->where('id', '[0-9]+')->name('infografis.show');
    Route::put('/infografis/{id}', [InfografisController::class, 'update'])->where('id', '[0-9]+')->name('infografis.update');
    Route::delete('/infografis/{id}', [InfografisController::class, 'destroy'])->where('id', '[0-9]+')->name('infografis.destroy');
    Route::delete('/infografis/file/{file}', [InfografisController::class, 'deleteFile'])->name('infografis.deleteFile');
    
    // Doa Management
    Route::get('/doa', [DoaController::class, 'index'])->name('doa.index');
    Route::post('/doa', [DoaController::class, 'store'])->name('doa.store');
    Route::get('/doa/{id}', [DoaController::class, 'show'])->where('id', '[0-9]+')->name('doa.show');
    Route::get('/doa/{id}/edit', [DoaController::class, 'edit'])->where('id', '[0-9]+')->name('doa.edit');
    Route::put('/doa/{id}', [DoaController::class, 'update'])->where('id', '[0-9]+')->name('doa.update');
    Route::delete('/doa/{id}', [DoaController::class, 'destroy'])->where('id', '[0-9]+')->name('doa.destroy');
<<<<<<< HEAD
=======

    //Jamaah
    Route::get('/jamaah', [JamaahController::class, 'index'])->name('jamaah.index');
    Route::post('/jamaah', [JamaahController::class, 'store'])->name('jamaah.store');
    Route::get('/jamaah/{id}', [JamaahController::class, 'show'])->name('jamaah.show');
    Route::put('/jamaah/{id}', [JamaahController::class, 'update'])->name('jamaah.update');
    Route::delete('/jamaah/{id}', [JamaahController::class, 'destroy'])->name('jamaah.destroy');
    Route::get('/jamaah/export', [JamaahController::class, 'export'])->name('jamaah.export');
>>>>>>> 43d2fb5e5102d14f9554c8fc45e830effb64474c
});