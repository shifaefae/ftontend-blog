<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\IklanController;
use App\Http\Controllers\EjurnalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController; // ← TAMBAH INI


Route::get('/register', [RegisterController::class, 'show'])->name('register');        // ← TAMBAH
Route::post('/register',[RegisterController::class, 'register'])->name('register.post'); 
// =============================================
//  AUTH (Guest only)
// =============================================
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'show'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =============================================
//  SEMUA HALAMAN PROTECTED (harus login)
// =============================================
Route::middleware('auth.session')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/list',        [BlogController::class, 'index'])->name('list');
        Route::get('/tambah',      [BlogController::class, 'create'])->name('tambah');
        Route::post('/',           [BlogController::class, 'store'])->name('store');
        Route::get('/edit/{id}',   [BlogController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [BlogController::class, 'update'])->name('update');
        Route::delete('/{id}',     [BlogController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/',                 [KategoriController::class, 'index'])->name('index');
        Route::post('/kategori',        [KategoriController::class, 'storeKategori'])->name('kategori.store');
        Route::put('/kategori/{id}',    [KategoriController::class, 'updateKategori'])->name('kategori.update');
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroyKategori'])->name('kategori.destroy');
        Route::post('/tag',             [KategoriController::class, 'storeTag'])->name('tag.store');
        Route::put('/tag/{id}',         [KategoriController::class, 'updateTag'])->name('tag.update');
        Route::delete('/tag/{id}',      [KategoriController::class, 'destroyTag'])->name('tag.destroy');
    });

    Route::prefix('iklan')->name('iklan.')->group(function () {
        Route::get('/',          [IklanController::class, 'index'])->name('list');
        Route::post('/',         [IklanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [IklanController::class, 'edit'])->name('edit');
        Route::put('/{id}',      [IklanController::class, 'update'])->name('update');
        Route::delete('/{id}',   [IklanController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('ejurnal')->name('ejurnal.')->group(function () {
        Route::get('/',               [EjurnalController::class, 'index'])->name('index');
        Route::post('/',              [EjurnalController::class, 'store'])->name('store');
        Route::put('/{id}',           [EjurnalController::class, 'update'])->name('update');
        Route::delete('/{id}',        [EjurnalController::class, 'destroy'])->name('destroy');
        Route::delete('/gambar/{id}', [EjurnalController::class, 'deleteGambar'])->name('gambar.destroy');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/',        [AdminController::class, 'index'])->name('index');
        Route::post('/',       [AdminController::class, 'store'])->name('store');
        Route::put('/{id}',    [AdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',         [ProfileController::class, 'index'])->name('index');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('update.password');
    });
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])
        ->name('profile.update.password');
});