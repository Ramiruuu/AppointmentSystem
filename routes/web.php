<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AdminAppointmentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('appointments', AppointmentController::class)->only(['create', 'store', 'index']);
    Route::delete('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    
    // API routes
    Route::get('/get-hospitals-by-service/{serviceId}', [AppointmentController::class, 'getHospitalsByService'])->name('api.hospitals.by-service');

    // Cancel and complete routes
    Route::get('/appointments/{appointment}/complete', [AppointmentController::class, 'markAsCompleted'])->name('appointments.complete');
    Route::get('/appointments/{appointment}/cancel-page', [AppointmentController::class, 'cancelPage'])->name('appointments.cancel-page');

    // Payment update route
    Route::patch('/appointments/{appointment}/payment', [AppointmentController::class, 'updatePayment'])->name('appointments.update-payment');

});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    Route::resource('services', ServiceController::class);
    Route::patch('/services/{service}/toggle', [ServiceController::class, 'toggleActive'])->name('services.toggle');
});