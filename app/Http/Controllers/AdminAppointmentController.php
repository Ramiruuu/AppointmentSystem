<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\Hospital;
use App\Notifications\AppointmentNotification;
use App\Mail\AppointmentBookedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date', 'after:' . now()->addHour()->toDateTimeString()],
            'hospital_id' => ['nullable', 'exists:hospitals,id'],
            'payment_method' => ['nullable', 'string', 'in:cash,bank_transfer,gcash,paymaya'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'payment_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $appointmentDate = \Carbon\Carbon::parse($request->appointment_date);
        $userId = Auth::id();
        $service = Service::find($request->service_id);

        // Double-booking check
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
            $locationLatitude = $request->location_latitude;
            $locationLongitude = $request->location_longitude;
            $locationAddress = $request->location_address;
            $preferredLocation = $request->preferred_location;
            
            if ($request->hospital_id) {
                $hospital = Hospital::find($request->hospital_id);
                if ($hospital) {
                    $locationLatitude = $hospital->latitude;
                    $locationLongitude = $hospital->longitude;
                    $locationAddress = $hospital->address;
                    $preferredLocation = $hospital->name;
                }
            }
            
            $appointment = Appointment::create([
                'user_id' => $userId,
                'service_id' => $request->service_id,
                'appointment_date' => $appointmentDate,
                'status' => 'pending',
                'hospital_id' => $request->hospital_id,
                'location_latitude' => $locationLatitude,
                'location_longitude' => $locationLongitude,
                'location_address' => $locationAddress,
                'preferred_location' => $preferredLocation,
                'payment_status' => 'unpaid',
                'payment_method' => $request->payment_method,
                'payment_reference' => $request->payment_reference,
                'payment_notes' => $request->payment_notes,
                'amount_paid' => 0,
            ]);

            $appointment->load('service', 'user', 'hospital');
            
            Mail::to($appointment->user->email)->send(new AppointmentBookedMail($appointment));
            Auth::user()->notify(new AppointmentNotification($appointment, 'booked'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to book appointment. Please try again.')->withInput();
        }

        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully! Please complete the payment.');
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