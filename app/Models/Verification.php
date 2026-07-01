<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    protected $table = 'verification';
    protected $fillable = [
        'admin_id', 'driver_id', 'doc_id', 'ver_status', 'rej_reason', 'license_expiry_date',
    ];

    // Fix the relationship - belongsTo Driver
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    // Add inverse relationship
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }
}