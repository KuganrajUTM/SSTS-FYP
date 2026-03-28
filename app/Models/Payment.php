<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payment';

    protected $fillable = [
        'parent_id',
        'driver_id',
        'child_id',
        'pay_date',
        'pay_status',
        'pay_amount',
        'issue_date',
        'payment_intent_id',
        'stripe_payment_id'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'pay_date' => 'date',
    ];

    public function driver(){
        return $this->belongsTo(Driver::class);
    }

    public function parent(){
        return $this->belongsTo(Parents::class);
    }

    public function child(){
        return $this->belongsTo(Child::class);
    }

    public function receipt(){
        return $this->hasOne(Receipt::class);
    }
}
