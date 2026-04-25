<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Notifications\AppointmentNotification;
use App\Mail\AppointmentBookedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $upcoming = $user->appointments()->with('service')->upcoming()->orderBy('appointment_date')->get();
        $past = $user->appointments()->with('service')->past()->orderBy('appointment_date', 'desc')->get();

        return view('appointments.index', compact('upcoming', 'past'));
    }

    public function create()
    {
        $services = Service::active()->orderBy('name')->get();
        $servicesJson = json_encode($services->map(fn($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'duration' => $s->duration_minutes,
            'price' => $s->price
        ])->values()->toArray());
        
        return view('appointments.create', compact('services', 'servicesJson'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id'       => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date', 'after:' . now()->addHour()->toDateTimeString()],
        ]);

        $appointmentDate = \Carbon\Carbon::parse($request->appointment_date);
        $userId = Auth::id();

        // Double-booking check: same user within 30 minutes
        $conflict = Appointment::where('user_id', $userId)
            ->whereNotIn('status', ['cancelled'])
            ->whereBetween('appointment_date', [
                $appointmentDate->copy()->subMinutes(30),
                $appointmentDate->copy()->addMinutes(30),
            ])->exists();

        if ($conflict) {
            return back()->withErrors(['appointment_date' => 'You already have an appointment within 30 minutes of this time.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $appointment = Appointment::create([
                'user_id'          => $userId,
                'service_id'       => $request->service_id,
                'appointment_date' => $appointmentDate,
                'status'           => 'pending',
            ]);

            $appointment->load('service', 'user');
            
            // Send email notification
            Mail::to($appointment->user->email)->send(new AppointmentBookedMail($appointment));
            
            // Send database notification
            Auth::user()->notify(new AppointmentNotification($appointment, 'booked'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to book appointment. Please try again.')->withInput();
        }

        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully! A confirmation email has been sent to your inbox.');
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:500'],
        ]);

        $appointment->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        $appointment->load('service');
        Auth::user()->notify(new AppointmentNotification($appointment, 'cancelled'));

        return back()->with('success', 'Appointment cancelled successfully.');
    }
}