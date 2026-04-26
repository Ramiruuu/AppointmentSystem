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
use Carbon\Carbon;

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
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $hospitals = Hospital::where('is_active', true)->get();

        return view('appointments.create', compact('services', 'hospitals'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'service_id'       => ['required', 'exists:services,id'],
                'appointment_date' => ['required', 'date'],
                'hospital_id'      => ['required', 'exists:hospitals,id'],
                'time_slot'        => ['required', 'string'],
                'payment_method'   => ['required', 'string'],
            ]);

            $appointmentDate = Carbon::parse($request->appointment_date);
            $userId = Auth::id();
            $timeSlot = $request->time_slot;

            // Check if slot is still available (max 10 per slot)
            $slotCount = Appointment::whereDate('appointment_date', $appointmentDate)
                ->where('time_slot', $timeSlot)
                ->where('hospital_id', $request->hospital_id)
                ->whereNotIn('status', ['cancelled'])
                ->count();

            if ($slotCount >= 10) {
                return back()->with('error', 'This time slot is already full. Please choose another slot.')->withInput();
            }

            // Get queue position
            $slotPosition = $slotCount + 1;
            $queueNumber = $appointmentDate->format('Ymd') . '-' . substr($timeSlot, 0, 1) . '-' . str_pad($slotPosition, 2, '0', STR_PAD_LEFT);

            // Get hospital details
            $hospital = Hospital::find($request->hospital_id);

            // Create appointment
            $appointment = Appointment::create([
                'user_id'            => $userId,
                'service_id'         => $request->service_id,
                'appointment_date'   => $appointmentDate,
                'status'             => 'pending',
                'hospital_id'        => $request->hospital_id,
                'location_latitude'  => $hospital->latitude ?? null,
                'location_longitude' => $hospital->longitude ?? null,
                'location_address'   => $hospital->address ?? null,
                'preferred_location' => $hospital->name ?? null,
                'time_slot'          => $timeSlot,
                'queue_number'       => $queueNumber,
                'slot_position'      => $slotPosition,
                'payment_status'     => 'unpaid',
                'payment_method'     => $request->payment_method,
                'amount_paid'        => 0,
                'payment_reference'  => $request->payment_reference ?? null,
                'payment_notes'      => $request->payment_notes ?? null,
            ]);

            $appointment->load('service', 'user', 'hospital');
            
            try {
                Mail::to($appointment->user->email)->send(new AppointmentBookedMail($appointment));
            } catch (\Exception $e) {
                Log::error('Email failed: ' . $e->getMessage());
            }
            
            Auth::user()->notify(new AppointmentNotification($appointment, 'booked'));

            return redirect()->route('dashboard')->with('success', "Appointment booked successfully! Queue: {$queueNumber}, Position: {$slotPosition} of 10.");

        } catch (\Exception $e) {
            Log::error('Appointment booking failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to book appointment. Please try again.')->withInput();
        }
    }

    public function getTimeSlots(Request $request)
    {
        try {
            $date = $request->get('date');
            $hospitalId = $request->get('hospital_id');
            
            if (!$date) {
                return response()->json([]);
            }
            
            $timeSlots = [
                'morning_1' => [
                    'key' => 'morning_1',
                    'label' => 'Morning (7:00 AM - 9:00 AM)',
                    'start' => '07:00',
                    'end' => '09:00'
                ],
                'morning_2' => [
                    'key' => 'morning_2',
                    'label' => 'Late Morning (10:00 AM - 12:00 PM)',
                    'start' => '10:00',
                    'end' => '12:00'
                ],
                'afternoon_1' => [
                    'key' => 'afternoon_1',
                    'label' => 'Afternoon (1:00 PM - 3:00 PM)',
                    'start' => '13:00',
                    'end' => '15:00'
                ],
                'afternoon_2' => [
                    'key' => 'afternoon_2',
                    'label' => 'Late Afternoon (4:00 PM - 6:00 PM)',
                    'start' => '16:00',
                    'end' => '18:00'
                ],
            ];
            
            $result = [];
            
            foreach ($timeSlots as $key => $slot) {
                $query = Appointment::whereDate('appointment_date', $date)
                    ->where('time_slot', $key)
                    ->whereNotIn('status', ['cancelled']);
                
                if ($hospitalId && $hospitalId !== 'null' && $hospitalId !== '' && $hospitalId !== 'undefined') {
                    $query->where('hospital_id', $hospitalId);
                }
                
                $count = $query->count();
                $available = 10 - $count;
                
                $result[$key] = [
                    'key' => $key,
                    'label' => $slot['label'],
                    'start' => $slot['start'],
                    'end' => $slot['end'],
                    'total_booked' => $count,
                    'available' => max(0, $available),
                    'is_full' => $available <= 0,
                    'percentage' => round(($count / 10) * 100)
                ];
            }
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Time slot error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
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

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    public function updatePayment(Request $request, Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'payment_method' => ['required', 'string'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'payment_reference' => ['nullable', 'string'],
        ]);

        $totalAmount = $appointment->service->price ?? 0;
        $amountPaid = $request->amount_paid;
        
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

        return back()->with('success', 'Payment information updated.');
    }
}