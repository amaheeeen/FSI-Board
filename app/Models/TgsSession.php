<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TgsSession extends Model
{
    protected $fillable = ['mutawwif_id', 'packet_id', 'channel_name', 'is_active'];
}
