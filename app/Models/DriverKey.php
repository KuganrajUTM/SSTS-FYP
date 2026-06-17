<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverKey extends Model
{
    protected $table = 'driver_keys';

    protected $fillable = ['key_code', 'used', 'used_at'];

    protected $casts = [
        'used'    => 'boolean',
        'used_at' => 'datetime',
    ];
}
