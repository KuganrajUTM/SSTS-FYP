<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverLocation extends Model
{
    protected $table = 'driver_locations';
    public $timestamps = false;

    protected $fillable = ['driver_id', 'lat', 'lng', 'timestamp'];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
