<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Agent has many transactions (bookings)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
