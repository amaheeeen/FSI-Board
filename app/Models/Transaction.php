<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Transaction extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        // Auto-generate transaction code on creation
        static::creating(function ($transaction) {
            $transaction->transaction_code = 'TRX-' . strtoupper(uniqid());
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function pilgrims()
    {
        return $this->hasMany(Pilgrim::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function calculateTotal($roomType = 'quad') // quad, triple, double
    {
        $price = match($roomType) {
            'double' => $this->package->price_double,
            'triple' => $this->package->price_triple,
            default => $this->package->price_quad,
        };

        return $price * $this->total_pax;
    }
}
