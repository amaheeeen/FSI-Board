<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packet extends Model
{
    protected $guarded = [];

    public function components()
    {
        return $this->hasMany(PacketComponent::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
