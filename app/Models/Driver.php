<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    protected $table = 'driver';
    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'VRN',
        'ver_status',
        'user_id',
        'city',
        'district',
        'bank_name',
        'bank_account_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function verification()
    {
        return $this->hasOne(Verification::class, 'driver_id');
    }

    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function docs(){
        return $this->hasMany(Document::class);
    }

    public function child(){
        return $this->belongsTo(Child::class);
    }
    public function passengers()
    {
        return $this->hasMany(Child::class);
    }

    public function children()
{
    return $this->hasMany(Child::class);
}

public function schedules()
{
    return $this->hasMany(Schedule::class);
}

}
