<?php

namespace App\Modules\VisitService\Models;

use Illuminate\Database\Eloquent\Model;

class VisitService extends Model
{
    protected $fillable = [
        'visit_id',
        'service_id',
        'price',
        'cost',
        'discount',
        'doctor_percentage',
        'clinic_percentage',
        'doctor_share',
        'clinic_share',
    ];

    protected $casts = [
        'price'             => 'decimal:2',
        'cost'              => 'decimal:2',
        'discount'          => 'decimal:2',
        'doctor_percentage' => 'decimal:2',
        'clinic_percentage' => 'decimal:2',
        'doctor_share'      => 'decimal:2',
        'clinic_share'      => 'decimal:2',
    ];

    public function visit()
    {
        return $this->belongsTo(\App\Modules\Visit\Models\Visit::class);
    }

    public function service()
    {
        return $this->belongsTo(\App\Modules\Service\Models\Service::class);
    }
}