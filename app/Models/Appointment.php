<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'service_id', 
        'appointment_date', 
        'status', 
        'cancellation_reason',
        'location_latitude',
        'location_longitude',
        'location_address',
        'preferred_location',
        'hospital_id'
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>', now())->whereNotIn('status', ['cancelled']);
    }

    public function scopePast($query)
    {
        return $query->where('appointment_date', '<', now());
    }

    public function getLocationDisplayAttribute()
    {
        if ($this->preferred_location) {
            return $this->preferred_location;
        }
        if ($this->location_address) {
            return $this->location_address;
        }
        if ($this->hospital) {
            return $this->hospital->name . ' - ' . $this->hospital->address;
        }
        return 'Not specified';
    }
}