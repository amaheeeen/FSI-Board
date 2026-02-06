<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamaahLocation extends Model
{
    protected $fillable = ['jamaah_id', 'lat', 'lng', 'recorded_at'];
}
