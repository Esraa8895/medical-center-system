<?php

namespace App\Modules\CenterExpense\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CenterExpense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'amount',
        'expense_date',
        'category',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
