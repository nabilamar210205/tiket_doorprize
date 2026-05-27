<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

// Dashboard Warga
Route::get('/', [TicketController::class, 'index'])->name('home');
Route::post('/booking', [TicketController::class, 'store'])->name('booking.store');
Route::get('/tickets/search', [TicketController::class, 'search'])->name('booking.search');

// Halaman Admin & Undian Doorprize
Route::prefix('admin')->group(function () {
    Route::get('/', [TicketController::class, 'adminIndex'])->name('admin.index');
    Route::post('/login', [TicketController::class, 'adminLogin'])->name('admin.login');
    Route::post('/logout', [TicketController::class, 'adminLogout'])->name('admin.logout');
    Route::post('/tickets/{id}/approve', [TicketController::class, 'approve'])->name('admin.approve');
    Route::delete('/tickets/{id}/cancel', [TicketController::class, 'cancel'])->name('admin.cancel');
    Route::get('/tickets/download-pdf', [TicketController::class, 'downloadPdf'])->name('admin.tickets.pdf');
    Route::post('/reset', [TicketController::class, 'reset'])->name('admin.reset');
});
