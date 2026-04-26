<?php

namespace App\Modules\Service\Models;

use App\Modules\Specialization\Models\Specialty;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'specialty_id',
        'price',
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
