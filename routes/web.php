<?php

use App\Http\Controllers\GuestAuthController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
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

// Guest authentication
Route::prefix('guests')->group(function () {
    Route::get('/login', [GuestAuthController::class, 'showLoginForm'])->name('guest.login.form');
    Route::post('/login', [GuestAuthController::class, 'login'])->name('guests.login');

    // Guest protected routes
    Route::middleware('auth:guest')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Guests/Dashboard');
        })->name('guests.dashboard');

        // Guest menu and orders
        Route::get('/menu', [OrderController::class, 'index'])->name('guests.menu');
        Route::post('/orders', [OrderController::class, 'store'])->name('guests.orders.store');
        Route::get('/orders', [OrderController::class, 'guestOrders'])->name('guests.orders');
        Route::post('/guests/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('guests.orders.cancel');

        // Guest checkout (if needed)
        Route::post('/{id}/checkout', [GuestController::class, 'checkout'])->name('guests.checkout');
    });
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Tables
    Route::resource('tables', TableController::class)->only([
        'index', 'store', 'update', 'destroy',
    ]);

    // Staff
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');

    // Rooms
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::post('/rooms/toggle/{room}', [RoomController::class, 'toggle'])->name('rooms.toggle');

    // Reservations
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::post('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservation.update');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    // Menu management
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::post('/menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
    Route::post('/menu/{menu}/toggle-stock', [MenuController::class, 'toggleStock'])->name('menu.toggleStock');

    // Guests management
    Route::resource('guests', GuestController::class)->only(['index', 'store']);
});
