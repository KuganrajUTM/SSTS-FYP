<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Child extends Model
{
    protected $table = 'child';

    protected $fillable = [
        'name',
        'school_name',
        'city',
        'district',
        'parent_id',
        'driver_id',
    ];

    
    public function payment(){
        return $this->hasMany(Payment::class);
    }

    public function parent()
    {
        return $this->belongsTo(Parents::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function receipt(){
        return $this->hasMany(Receipt::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
