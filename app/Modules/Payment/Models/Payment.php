<?php

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'visit_id',
        'patient_id',
        'amount',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function visit()
    {
        return $this->belongsTo(\App\Modules\Visit\Models\Visit::class);
    }

    public function patient()
    {
        return $this->belongsTo(\App\Modules\Patient\Models\Patient::class);
    }
}
