<?php

namespace App\Models;

use App\Models\Concerns\SoftDeletableRecord;
use App\Models\Contracts\HasRecordQueries;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Folder extends Model implements HasRecordQueries
{
    use SoftDeletes;
    use SoftDeletableRecord;

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
     * Override: folders also keep a slug in sync.
     */
    public static function renameRecord(int $id, string $name): void
    {
        static::where('id', $id)->update([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }

    /**
     * @return Collection<int, Folder>
     */
    public static function trashedForUser(int $userId): Collection
    {
        return static::query()
            ->onlyTrashed()
            ->where('user_id', $userId)
            ->get();
    }

    /**
     * @return Collection<int, Folder>
     */
    public static function rootForUser(int $userId): Collection
    {
        return static::where('user_id', $userId)
            ->whereNull('parent_id')
            ->get();
    }

    /**
     * @return Collection<int, Folder>
     */
    public static function childrenOf(int $folderId): Collection
    {
        return static::where('parent_id', $folderId)->get();
    }

    /**
     * Walk up the tree and return ancestors (root first).
     *
     * @return array<int, Folder>
     */
    public function ancestors(): array
    {
        $ancestors = [];
        $current = $this;

        while ($current->parent_id !== null) {
            $current = static::find($current->parent_id);
            if (! $current) {
                break;
            }
            array_unshift($ancestors, $current);
        }

        return $ancestors;
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
