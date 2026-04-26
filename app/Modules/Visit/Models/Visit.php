<?php

namespace App\Modules\Visit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'treatment_plan_id',
        'appointment_id',
        'total_amount',
        'paid_cost',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_cost'    => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(\App\Modules\Patient\Models\Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(\App\Modules\Doctor\Models\Doctor::class);
    }

    public function treatmentPlan()
    {
        return $this->belongsTo(\App\Modules\TreatmentPlan\Models\TreatmentPlan::class);
    }

    public function visitServices()
    {
        return $this->hasMany(\App\Modules\VisitService\Models\VisitService::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Modules\Payment\Models\Payment::class);
    }
}
