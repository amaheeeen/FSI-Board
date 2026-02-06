<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['agent_id', 'name', 'phone', 'status', 'notes'];

    const STATUS_INTERESTED = 'interested';
    const STATUS_DEPOSIT = 'deposit';
    const STATUS_PAID = 'paid';
}
