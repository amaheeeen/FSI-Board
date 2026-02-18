<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getAvailableQuotaAttribute()
    {
        // Sum total_pax from confirmed transactions (not cancelled)
        $booked = $this->transactions()
            ->where('status', '!=', 'Cancelled')
            ->sum('total_pax');
            
        return $this->quota - $booked;
    }
}
