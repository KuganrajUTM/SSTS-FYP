<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Verification extends Model
{
    use HasFactory;

    // Specify the table if it's not the plural form of the model name
    protected $table = 'verification';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'admin_id',            // Admin who did the verification
        'driver_id',           // ID of the driver
        'doc_id',              // Document ID associated with the driver
        'ver_status',          // Verification status (pending, approved, rejected)
        'rej_reason',          // Rejection reason (nullable)
    ];

    public function driver()
{
    return $this->belongsTo(Driver::class, 'driver_id'); // 'driver_id' is the foreign key in the verification table
}

}
