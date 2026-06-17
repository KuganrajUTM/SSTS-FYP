<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SosMessage extends Model
{
    protected $table = 'sos_messages';

    protected $fillable = [
        'driver_id',
        'audio_path',
        'transcript',
        'deleted_by_admin',
        'deleted_by_parent',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
