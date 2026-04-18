<?php

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Visit\Models\Visit;
use App\Modules\Patient\Models\Patient;

class Payment extends Model
{
    protected $fillable = [
        'visit_id',
        'patient_id',
        'amount',
        'exchange_rate',
        'notes',
    ];

    protected $casts = [
        'amount'        => 'decimal:2',
        'exchange_rate' => 'decimal:4',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
