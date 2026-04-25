<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Notifications\AppointmentNotification;
use Illuminate\Http\Request;

class AdminAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['user', 'service'])->latest('appointment_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('appointment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('appointment_date', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $appointments = $query->paginate(15)->withQueryString();

        return view('admin.appointments.index', compact('appointments'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $appointment->update(['status' => $request->status]);
        $appointment->load('service');
        $appointment->user->notify(new AppointmentNotification($appointment, 'status_updated'));

        return back()->with('success', 'Appointment status updated.');
    }
}