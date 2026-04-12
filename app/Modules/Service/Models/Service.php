<?php

namespace App\Modules\Service\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'specialty_id', 'price'];
}