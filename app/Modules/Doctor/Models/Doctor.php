<?php

namespace App\Modules\Doctor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Specialization\Models\Specialty;
use App\Modules\Visit\Models\Visit;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'specialty_id',
        'default_percentage',
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
