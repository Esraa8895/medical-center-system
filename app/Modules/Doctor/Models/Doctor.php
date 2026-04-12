<?php

namespace App\Modules\Doctor\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['name', 'specialty_id', 'default_percentage'];
}