<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\GuestAuthController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TableController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::prefix('guests')->group(function () {
    Route::get('/login', [GuestAuthController::class, 'showLoginForm'])->name('guest.login.form');
    Route::post('/login', [GuestAuthController::class, 'login'])->name('guests.login');
    Route::post('/logout', [GuestAuthController::class, 'logout'])->name('guests.logout');

    Route::middleware('auth:guest')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Guests/Dashboard');
        })->name('guests.dashboard');

        Route::get('/guest-menu', [OrderController::class, 'guestMenu'])->name('guests.menu');
        Route::post('/orders', [OrderController::class, 'store'])->name('guests.orders.store');
        Route::get('/orders', [OrderController::class, 'guestOrders'])->name('guests.orders');
        Route::post('/guests/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('guests.orders.cancel');

        Route::get('/reservations', [ReservationController::class, 'guestView'])->name('guests.reservations');
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
        Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

        Route::post('/{id}/checkout', [GuestController::class, 'checkout'])->name('guests.checkout');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('tables', TableController::class)->only([
        'index', 'store', 'update', 'destroy',
    ]);

    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/menu', [MenuController::class, 'staffMenu'])->name('menu.staff');

    Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
    Route::post('/promotions', [PromotionController::class, 'store'])->name('promotions.store');

    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::post('/rooms/toggle/{room}', [RoomController::class, 'toggle'])->name('rooms.toggle');

    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::post('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'destroy'])->name('reservations.cancel');

    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::post('/menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
    Route::post('/menu/{menu}/toggle-stock', [MenuController::class, 'toggleStock'])->name('menu.toggleStock');

    Route::resource('guests', GuestController::class)->only(['index', 'store']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::post('/orders/{order}/approve', [OrderController::class, 'approveOrder'])->name('orders.approve');

    Route::delete('/orders/{order}', [OrderController::class, 'removeItem'])->name('orders.destroy');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

});
