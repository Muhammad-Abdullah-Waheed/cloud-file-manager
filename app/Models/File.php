<?php

namespace App\Models;

use App\Models\Concerns\SoftDeletableRecord;
use App\Models\Contracts\HasRecordQueries;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model implements HasRecordQueries
{
    use SoftDeletes;
    use SoftDeletableRecord;

    protected $fillable = [
        'user_id',
        'name',
        'parent_id',
        'deleted_at',
        'mime_type',
    ];

    /**
     * Files inside a folder (or root when null) for a given owner.
     *
     * @return Collection<int, File>
     */
    public static function filesInFolderForUser(?int $parentId, int $userId): Collection
    {
        return static::where('user_id', $userId)
            ->when(
                $parentId === null,
                fn ($query) => $query->whereNull('parent_id'),
                fn ($query) => $query->where('parent_id', $parentId),
            )
            ->with('currentVersion')
            ->get();
    }

    /**
     * Override: trashed files also load their current version.
     *
     * @return Collection<int, File>
     */
    public static function trashedForUser(int $userId): Collection
    {
        return static::onlyTrashed()
            ->where('user_id', $userId)
            ->with('currentVersion')
            ->get();
    }

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
