<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $table = 'receipt';

    protected $fillable = [
        'pay_id',
        'rec_date',
        'rec_status',
        'rec_amount',
        'rec_num',
        'child_id',
        'parent_id',
        'payment_method'
    ];

    protected $casts = [
        'rec_date' => 'date',
    ];

    public function payment(){
        return $this->belongsTo(Payment::class);
    }
    public function parent(){
        return $this->belongsTo(Parents::class);
    }

    public function child(){
        return $this->belongsTo(Child::class);
    }
}
