<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverSalary extends Model
{
    protected $fillable = ['driver_id', 'amount', 'month', 'year', 'status', 'paid_at', 'notes', 'receipt_pdf'];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
