<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $guarded = [];

    public function transaction_ref()
    {
        return $this->morphTo();
    }

    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }
}
