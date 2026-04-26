<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AdminAppointmentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

require __DIR__.'/auth.php';

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();

        $totalAppointments     = $user->appointments()->count();
        $upcomingAppointments  = $user->appointments()->upcoming()->whereNotIn('status', ['cancelled'])->count();
        $completedAppointments = $user->appointments()->where('status', 'completed')->count();
        $cancelledAppointments = $user->appointments()->where('status', 'cancelled')->count();

        $upcomingList = $user->appointments()
            ->with('service', 'hospital')
            ->upcoming()
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('appointment_date')
            ->limit(5)
            ->get();

        $upcomingPercentage  = $totalAppointments > 0 ? round(($upcomingAppointments  / $totalAppointments) * 100) : 0;
        $cancelledPercentage = $totalAppointments > 0 ? round(($cancelledAppointments / $totalAppointments) * 100) : 0;
        $completedPercentage = $totalAppointments > 0 ? round(($completedAppointments / $totalAppointments) * 100) : 0;

        return view('dashboard', compact(
            'totalAppointments',
            'upcomingAppointments',
            'completedAppointments',
            'cancelledAppointments',
            'upcomingList',
            'upcomingPercentage',
            'cancelledPercentage',
            'completedPercentage'
        ));
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('appointments', AppointmentController::class)->only(['create', 'store', 'index']);
    Route::delete('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::patch('/appointments/{appointment}/payment', [AppointmentController::class, 'updatePayment'])->name('appointments.update-payment');

    Route::get('/get-time-slots', [AppointmentController::class, 'getTimeSlots'])->name('api.time-slots');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    Route::resource('services', ServiceController::class);
    Route::patch('/services/{service}/toggle', [ServiceController::class, 'toggleActive'])->name('services.toggle');
});