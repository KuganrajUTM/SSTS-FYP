<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parents extends Model
{
    protected $table = 'parent';
    public $timestamps = false;

    protected $fillable = [
        'location',
        'user_id'
    ];


    public function payment(){
        return $this->hasMany(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function child(){
        return $this->hasMany(Child::class);
    }

    public function receipt(){
        return $this->hasMany(Receipt::class);
    }
    public function children()
    {
        return $this->hasMany(Child::class, 'parent_id');
    }
}
