<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'entity_id',
        'entity_type',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Polymorphic — points to File or Folder
    public function entity()
    {
        return $this->morphTo();
    }
}
