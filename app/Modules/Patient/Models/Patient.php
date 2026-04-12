<?php

namespace App\Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'age',
        'previous_diseases',
    ];

    public function appointments()
    {
        return $this->hasMany(\App\Modules\Appointment\Models\Appointment::class);
    }

    public function visits()
    {
        return $this->hasMany(\App\Modules\Visit\Models\Visit::class);
    }

    public function treatmentPlans()
    {
        return $this->hasMany(\App\Modules\TreatmentPlan\Models\TreatmentPlan::class);
    }
}