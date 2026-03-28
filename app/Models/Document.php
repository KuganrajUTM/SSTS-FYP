<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'document';
    public $timestamps = false;

    protected $fillable = [
        'driver_id',
        'docs',
        'license',
        'created_at',
        'updated_at'
    ];
    

    public function driver(){
        return $this->belongsTo(Driver::class);
    }
}
