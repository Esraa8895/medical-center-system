<?php

namespace App\Modules\Doctor\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Specialization\Models\Specialty;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialty_id',
        'default_percentage',
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}

