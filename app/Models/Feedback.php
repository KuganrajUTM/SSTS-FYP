<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = [
        'from_user_id', 'to_driver_id', 'to_child_id', 'to_parent_id',
        'type', 'rating', 'comment', 'status', 'manager_remark',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toDriver()
    {
        return $this->belongsTo(Driver::class, 'to_driver_id');
    }

    public function toChild()
    {
        return $this->belongsTo(Child::class, 'to_child_id');
    }

    public function toParent()
    {
        return $this->belongsTo(Parents::class, 'to_parent_id');
    }
}
