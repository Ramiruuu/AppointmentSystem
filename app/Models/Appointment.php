<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'service_id', 'appointment_date', 'status', 'cancellation_reason',
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

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('appointment_date', '<', now());
    }
}