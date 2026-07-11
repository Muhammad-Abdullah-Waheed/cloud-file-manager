<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Folder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'user_id',
        'deleted_at',
    ];

    protected static function booted(): void
    {
        static::creating(function ($folder) {
            $folder->slug = Str::slug($folder->name);
        });
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    /**
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Folder::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function shared()
    {
        return $this->morphMany(Shared::class, 'shared');
    }
}
