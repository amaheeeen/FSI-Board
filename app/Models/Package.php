<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'name',
        'departure_date',
        'return_date', // We should probably remove return_date if we use duration_days, or calculate it?
        // Controller validation has both? No, creating only has duration_days!
        // View create.blade.php likely has duration_days input.
        // But table has return_date.
        // So we need to calculate return_date from departure_date + duration_days in Controller.
        'price_quad',
        'price_triple',
        'price_double',
        'hotel_makkah',
        'hotel_madinah',
        'quota',
        'status',
        'duration_days', // Add this
    ];

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
