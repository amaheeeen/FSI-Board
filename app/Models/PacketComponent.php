<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PacketComponent extends Model
{
    protected $guarded = [];

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }
}
