<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\IklanController;
use App\Http\Controllers\EJurnalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;


use App\Http\Controllers\AuthController;

Route::get('/set-dummy-user', function () {
    session([
        'user' => [
            'name'     => 'Super Admin',
            'email'    => 'admin@portal.test',
            'role'     => 'Administrator',
            'password' => '********',
            'photo'    => 'https://ui-avatars.com/api/?name=Super+Admin&background=0D8ABC&color=fff'
        ]
    ]);

    return redirect('pages.profile');
});
Route::prefix('profile')->name('profile.')->group(function () {

    Route::get('/', [ProfileController::class, 'index'])
        ->name('index');

    Route::put('/password', [ProfileController::class, 'updatePassword'])
        ->name('update.password');
});

Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])
    ->name('profile.update.password');

Route::get('/login', function () {
    return view('component.Login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', function () {
    session()->flush();
    return redirect('/login');
})->name('logout');


Route::view('/','pages.Dashboard');
// Route::view('/blog/list','pages.Listblog') ->name('blog.list');
// Route::view('/blog','pages.blog') ->name('blog.store');
// Route::view('/blog/tambah','pages.Tambahblog');
Route::view('/kategori','pages.Kategori');
Route::view('/iklan','pages.Iklan');
Route::view('/ejurnal','pages.Ejurnal');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('/', [AdminController::class, 'store'])->name('store');
    Route::put('/{id}', [AdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminController::class, 'destroy'])->name('destroy');
});


Route::get('/blog/edit/{id}', [BlogController::class, 'edit'])
     ->name('blog.edit');

Route::put('/blog/update/{id}', [BlogController::class, 'update'])
     ->name('blog.update');

// Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/blog/tambah', [BlogController::class, 'create'])->name('blog.tambah');
Route::get('/blog/list', [BlogController::class, 'index'])->name('blog.list');
Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
// Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
// Route::get('/iklan', [IklanController::class, 'index'])->name('iklan');
// Route::get('/ejurnal', [EJurnalController::class, 'index'])->name('ejurnal');
// Route::get('/admin', [AdminController::class, 'index'])->name('admin');
 
