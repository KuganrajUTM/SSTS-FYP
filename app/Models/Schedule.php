<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedule';

    protected $fillable = [
        'time_slot',
        'location',
        'day',
        'driver_id'
    ];
    
    public function driver(){
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    // A schedule belongs to a child
    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
