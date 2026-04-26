<?php

namespace App\Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'service_id',
        'appointment_date',
        'type',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(\App\Modules\Patient\Models\Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(\App\Modules\Doctor\Models\Doctor::class);
    }

    public function service()
    {
        return $this->belongsTo(\App\Modules\Service\Models\Service::class);
    }
}
