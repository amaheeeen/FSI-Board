<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Jamaah extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'passport_expiry' => 'date',
        'dob' => 'date',
        'password' => 'hashed',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id'); // Assuming User model for now, or Agent model if I find it.
    }

    public function mahram()
    {                     
        return $this->belongsTo(Jamaah::class, 'mahram_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
