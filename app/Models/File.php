<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'parent_id',
        'deleted_at',
        'mime_type',
    ];

    public function getExtensionAttribute(): string
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    // Current version — latest by version_number

    /**
     * @return HasOne
     */
    public function currentVersion()
    {
        return $this->hasOne(FileVersion::class)
            ->latestOfMany('version_number');
    }

    // All versions
    public function versions()
    {
        return $this->hasMany(FileVersion::class);
    }

    // Owner
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Parent folder — explicit FK because column is parent_id not folder_id
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    // Shares — polymorphic
    public function shares()
    {
        return $this->morphMany(Shared::class, 'shared');
    }
}
