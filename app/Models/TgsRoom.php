<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TgsRoom extends Model
{
    protected $fillable = ['transaction_id', 'mutawwif_id', 'session_id', 'is_active', 'started_at', 'ended_at'];
}
