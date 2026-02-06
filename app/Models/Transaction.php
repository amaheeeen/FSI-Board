<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function jamaahs()
    {
        return $this->belongsToMany(Jamaah::class, 'transaction_details');
    }

    public function journal()
    {
        return $this->morphOne(Journal::class, 'transaction_ref');
    }
}
