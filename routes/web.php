<?php

use App\Http\Controllers\Admin\HallController;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::controller(EventController::class)->group(function () {
   Route::get('/', 'index')->name('index');
   Route::get('/event/{event}', 'show')->name('event.show');
});

Route::controller(BookingController::class)->prefix('/bookings')->group(function () {
    Route::post('/', 'store')->name('bookings.store');
    Route::get('/success-payment', 'successPayment')->name('success.payment');
    Route::post('/webhook', 'webhook')->name('webhook');
});


Route::get('/ticket/qr/{qr_token}', [TicketController::class, 'qr'])->name('tickets.qr');
Route::get('/tickets/{booking}/download-pdf', [TicketController::class, 'downloadPdf'])->name('tickets.download.pdf');


Route::get('/admin', [IndexController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    Route::controller(ProfileController::class)->group(function () {
       Route::get('/profile', 'edit')->name('profile.edit');
       Route::patch('/profile', 'update')->name('profile.update');
       Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::resource('halls', HallController::class)->except(['show']);
    Route::resource('events', \App\Http\Controllers\Admin\EventController::class)->except(['show']);
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index']);
});

require __DIR__.'/auth.php';
