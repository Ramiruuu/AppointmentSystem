<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\Hospital;
use App\Notifications\AppointmentNotification;
use App\Mail\AppointmentBookedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $upcoming = $user->appointments()->with('service', 'hospital')->upcoming()->orderBy('appointment_date')->get();
        $past = $user->appointments()->with('service', 'hospital')->past()->orderBy('appointment_date', 'desc')->get();

        return view('appointments.index', compact('upcoming', 'past'));
    }

    public function create()
    {
        $services = Service::active()->orderBy('name')->get();
        $hospitals = Hospital::where('is_active', true)->get();

        return view('appointments.create', compact('services', 'hospitals'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'service_id'       => ['required', 'exists:services,id'],
                'appointment_date' => ['required', 'date', 'after:' . now()->addHour()->toDateTimeString()],
                'hospital_id'      => ['nullable', 'exists:hospitals,id'],
                'payment_method'   => ['nullable', 'string'],
            ]);

            $appointmentDate = \Carbon\Carbon::parse($request->appointment_date);
            $userId = Auth::id();

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

            // Get hospital details if selected
            $locationLatitude = null;
            $locationLongitude = null;
            $locationAddress = null;
            $preferredLocation = null;
            
            if ($request->hospital_id) {
                $hospital = Hospital::find($request->hospital_id);
                if ($hospital) {
                    $locationLatitude = $hospital->latitude;
                    $locationLongitude = $hospital->longitude;
                    $locationAddress = $hospital->address;
                    $preferredLocation = $hospital->name;
                }
            }

            // Create appointment
            $appointment = Appointment::create([
                'user_id'            => $userId,
                'service_id'         => $request->service_id,
                'appointment_date'   => $appointmentDate,
                'status'             => 'pending',
                'hospital_id'        => $request->hospital_id,
                'location_latitude'  => $locationLatitude,
                'location_longitude' => $locationLongitude,
                'location_address'   => $locationAddress,
                'preferred_location' => $preferredLocation,
                'payment_status'     => 'unpaid',
                'payment_method'     => $request->payment_method ?? null,
                'amount_paid'        => 0,
            ]);

            $appointment->load('service', 'user', 'hospital');
            
            // Send email notification (wrap in try-catch so booking doesn't fail if email fails)
            try {
                Mail::to($appointment->user->email)->send(new AppointmentBookedMail($appointment));
            } catch (\Exception $e) {
                Log::error('Email failed: ' . $e->getMessage());
            }
            
            // Send database notification
            Auth::user()->notify(new AppointmentNotification($appointment, 'booked'));

            return redirect()->route('dashboard')->with('success', 'Appointment booked successfully! A confirmation email has been sent to your inbox.');

        } catch (\Exception $e) {
            Log::error('Appointment booking failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to book appointment. Please try again.')->withInput();
        }
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
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        $appointment->load('service');
        Auth::user()->notify(new AppointmentNotification($appointment, 'cancelled'));

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    public function getHospitalsByService($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $hospitals = Hospital::where('is_active', true)
            ->where('services_offered', 'LIKE', '%' . $service->name . '%')
            ->get();

        return response()->json($hospitals);
    }

    public function markAsCompleted(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $appointment->update([
            'status' => 'completed',
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment marked as completed!');
    }

    public function cancelPage(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        return view('appointments.cancel', compact('appointment'));
    }

    public function updatePayment(Request $request, Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'payment_method' => ['required', 'string', 'in:cash,bank_transfer,gcash,paymaya'],
            'payment_reference' => ['required_if:payment_method,bank_transfer,gcash,paymaya', 'nullable', 'string', 'max:100'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'payment_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $service = $appointment->service;
        $totalAmount = $service->price ?? 0;
        $amountPaid = $request->amount_paid;

        // Determine payment status
        if ($amountPaid >= $totalAmount && $totalAmount > 0) {
            $paymentStatus = 'paid';
            $paidAt = now();
        } elseif ($amountPaid > 0 && $amountPaid < $totalAmount) {
            $paymentStatus = 'partial';
            $paidAt = null;
        } else {
            $paymentStatus = 'unpaid';
            $paidAt = null;
        }

        $appointment->update([
            'payment_status' => $paymentStatus,
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'amount_paid' => $amountPaid,
            'payment_notes' => $request->payment_notes,
            'paid_at' => $paidAt,
        ]);

        $message = $paymentStatus === 'paid' ? 'Payment completed successfully!' : 'Payment information updated.';
        return back()->with('success', $message);
    }
}