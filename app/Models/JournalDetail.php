<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model
{
    protected $guarded = [];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function coa()
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }
}
