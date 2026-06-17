<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverKeyRequest extends Model
{
    protected $table = 'driver_key_requests';

    protected $fillable = ['name', 'email', 'contact', 'status', 'fulfilled_at'];

    protected $casts = [
        'fulfilled_at' => 'datetime',
    ];
}
