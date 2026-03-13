<?php

use App\Http\Controllers\Admin\HallController;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'index'])->name('index');
Route::get('/event/{event}', [EventController::class, 'show'])->name('event.show');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/success-payment', [BookingController::class, 'success_payment'])->name('success.payment');
Route::post('/webhook', [BookingController::class, 'webhook'])->name('webhook');
Route::get('/ticket/qr/{qr_token}', [TicketController::class, 'qr'])->name('tickets.qr');
Route::get('/tickets/{booking}/download-pdf', [TicketController::class, 'downloadPdf'])
    ->name('tickets.download.pdf');


Route::get('/admin', [IndexController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('halls', HallController::class)->except(['show']);
    Route::resource('events', \App\Http\Controllers\Admin\EventController::class)->except(['show']);
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index']);
});

require __DIR__.'/auth.php';
