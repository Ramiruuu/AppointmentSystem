<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;

class TimeSlotService
{
    const MAX_PER_SLOT = 10;
    
    const TIME_SLOTS = [
        'morning_1' => ['label' => 'Morning (7:00 AM - 9:00 AM)', 'start' => '07:00', 'end' => '09:00'],
        'morning_2' => ['label' => 'Late Morning (10:00 AM - 12:00 PM)', 'start' => '10:00', 'end' => '12:00'],
        'afternoon_1' => ['label' => 'Afternoon (1:00 PM - 3:00 PM)', 'start' => '13:00', 'end' => '15:00'],
        'afternoon_2' => ['label' => 'Late Afternoon (4:00 PM - 6:00 PM)', 'start' => '16:00', 'end' => '18:00'],
    ];
    
    public function getAvailableSlots($date, $hospitalId = null)
    {
        $slots = [];
        
        foreach (self::TIME_SLOTS as $key => $slot) {
            $query = Appointment::whereDate('appointment_date', $date)
                ->where('time_slot', $key)
                ->whereNotIn('status', ['cancelled']);
                
            if ($hospitalId) {
                $query->where('hospital_id', $hospitalId);
            }
            
            $count = $query->count();
            $available = self::MAX_PER_SLOT - $count;
            
            $slots[$key] = [
                'key' => $key,
                'label' => $slot['label'],
                'start' => $slot['start'],
                'end' => $slot['end'],
                'total_booked' => $count,
                'available' => max(0, $available),
                'is_full' => $available <= 0,
                'percentage' => round(($count / self::MAX_PER_SLOT) * 100)
            ];
        }
        
        return $slots;
    }
    
    public function isSlotAvailable($date, $timeSlot, $hospitalId = null)
    {
        $query = Appointment::whereDate('appointment_date', $date)
            ->where('time_slot', $timeSlot)
            ->whereNotIn('status', ['cancelled']);
            
        if ($hospitalId) {
            $query->where('hospital_id', $hospitalId);
        }
        
        $count = $query->count();
        return $count < self::MAX_PER_SLOT;
    }
    
    public function getQueueNumber($date, $timeSlot, $hospitalId = null)
    {
        $query = Appointment::whereDate('appointment_date', $date)
            ->where('time_slot', $timeSlot)
            ->whereNotIn('status', ['cancelled']);
            
        if ($hospitalId) {
            $query->where('hospital_id', $hospitalId);
        }
        
        $count = $query->count();
        $position = $count + 1;
        $queueNumber = date('Ymd') . '-' . substr($timeSlot, 0, 1) . '-' . str_pad($position, 2, '0', STR_PAD_LEFT);
        
        return [
            'position' => $position,
            'queue_number' => $queueNumber
        ];
    }
}