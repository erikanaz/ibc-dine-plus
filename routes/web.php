<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Models\Menu;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Menu
    Route::resource('/menus', MenuController::class, ['as' => 'admin']);
    Route::patch('/menus/{id}/update-status', [MenuController::class, 'updateStatus'])->name('admin.menus.update-status');

    // Table
    Route::resource('/tables', TableController::class, ['as' => 'admin']);
    Route::patch('/tables/{table}/update-status', [TableController::class, 'updateStatus'])->name('admin.tables.update-status');
    
    // Reservation
    Route::get('/reservations', [ReservationController::class, 'index'])->name('admin.reservations.index');
    Route::get('/reservations/{id}', [ReservationController::class, 'show'])->name('admin.reservations.show');
    Route::post('/reservations/update-status', [ReservationController::class, 'updateStatus'])->name('admin.reservations.updateStatus');

    // Promo
    Route::resource('/promos', PromoController::class, ['as' => 'admin']);

    // Route::get('/reservations', [AdminReservationController::class, 'index'])->name('admin.reservations.index');
    // Route::get('/reservations/{id}', [AdminReservationController::class, 'show'])->name('admin.reservations.show');
    // Route::post('/reservations/update-status', [AdminReservationController::class, 'updateStatus'])->name('admin.reservations.updateStatus');
});

// Route::get('/admin/dashboard', function () {
//     return view('admin.dashboard');
// })->middleware(['auth', 'role:admin'])->name('admin.dashboard');

// Route::get('/admin/manage-tables', function () {
//     return view('admin.manage-menus');
// })->middleware(['auth', 'role:admin'])->name('admin.manage-menus');

// Route::get('/homepage', function () {
//     return view('homepage'); // buat file resources/views/homepage.blade.php
// })->middleware(['auth', 'role:member'])->name('homepage');

Route::get('/homepage', [HomepageController::class, 'index'])->name('homepage');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth', 'role:member']], function () {
    Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');
    Route::post('/reservation/store', [ReservationController::class, 'store'])->name('reservation.store');
    // Route::post('/reservation/pilih-meja', [ReservationController::class, 'pilihMeja'])->name('reservation.pilihMeja');
    // Route::post('/reservation/simpan', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/reservasi/sukses', [ReservationController::class, 'success'])
        ->name('reservation.success');
    // / ✅ Tambahkan route riwayat reservasi
    Route::get('/reservation/history', [ReservationController::class, 'history'])
        ->name('reservation.history');
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/checkout', [OrderController::class, 'checkout'])->name('order.checkout'); // ← ini ditambahkan
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    // ✅ Tambahkan ini untuk Midtrans payment request
    Route::post('/pay', [OrderController::class, 'pay'])->name('order.pay');
    Route::get('/order/{order}/success', [OrderController::class, 'success'])->name('order.success');
    Route::get('/order/{order}/pending', [OrderController::class, 'pending'])->name('order.pending');
});

// Route::post('/pay', [\App\Http\Controllers\PaymentController::class, 'pay']);
// Route::post('/pay', [OrderController::class, 'pay'])->name('order.pay');


require __DIR__ . '/auth.php';
