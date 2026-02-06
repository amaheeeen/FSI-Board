<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $guarded = [];

    public function journalDetails()
    {
        return $this->hasMany(JournalDetail::class);
    }
}
