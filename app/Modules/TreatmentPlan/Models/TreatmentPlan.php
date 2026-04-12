<?php

namespace App\Modules\TreatmentPlan\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentPlan extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'service_id',
        'total_sessions',
        'completed_sessions',
        'expected_total',
        'status',
        'notes',
        'discount',
    ];

    protected $casts = [
        'expected_total' => 'decimal:2',
        'discount'       => 'decimal:2',
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

    public function visits()
    {
        return $this->hasMany(\App\Modules\Visit\Models\Visit::class);
    }
}