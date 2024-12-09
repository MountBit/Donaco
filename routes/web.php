<?php

use App\Http\Controllers\DonationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentNotificationController;

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('projects', ProjectController::class);
    Route::resource('donations', 'App\Http\Controllers\Admin\DonationController');
});

Route::get('/', [DonationController::class, 'index'])->name('donation.index');
Route::post('/donations', [DonationController::class, 'store'])->name('donations.stored');
Route::get('/donations/status/{external_reference}', [PaymentNotificationController::class, 'checkPaymentStatus'])->name('donations.status');

Route::get('register', function () {
    return redirect('/login');
});

require __DIR__.'/auth.php';
