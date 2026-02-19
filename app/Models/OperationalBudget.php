<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationalBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_type',
        'start_date',
        'end_date',
        'category',
        'allocated_amount',
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
    ];
}
