<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TableController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('tables', TableController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
});

Route::resource('guests', GuestController::class)->only(['index', 'store']);
Route::post('/guests/{id}/checkout', [GuestController::class, 'checkout'])->name('guests.checkout');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::post('/rooms/toggle/{room}', [RoomController::class, 'toggle'])->name('rooms.toggle');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::post('/menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
    Route::post('/menu/{menu}/toggle-stock', [MenuController::class, 'toggleStock'])->name('menu.toggleStock');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
