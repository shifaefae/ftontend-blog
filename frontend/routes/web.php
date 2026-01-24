<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\IklanController;
use App\Http\Controllers\EJurnalController;
use App\Http\Controllers\AdminController;

// Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::view('/','pages.Dashboard');
Route::view('/blog/list','pages.Listblog');

// Route::get('/blog/tambah', [BlogController::class, 'create'])->name('blog.tambah');
// Route::get('/blog/list', [BlogController::class, 'index'])->name('blog.list');
// Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
// Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
// Route::get('/iklan', [IklanController::class, 'index'])->name('iklan');
// Route::get('/ejurnal', [EJurnalController::class, 'index'])->name('ejurnal');
// Route::get('/admin', [AdminController::class, 'index'])->name('admin');
 
