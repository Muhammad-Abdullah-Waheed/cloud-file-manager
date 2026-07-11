<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shared extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'permission',
        'shared_type',
        'shared_id',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
    public function receiver()
    {
        return $this->belongsTo(User::class);
    }
    public function shared()
    {
        return $this->morphTo();
    }
}
