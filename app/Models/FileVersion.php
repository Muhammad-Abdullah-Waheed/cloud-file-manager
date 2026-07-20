<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class FileVersion extends Model
{
    protected $fillable = [
        'file_id',
        'path',
        'size',
        'version_number',
        'user_id',
    ];

    /**
     * @param  array<string, mixed>  $data
     */
    public static function storeVersion(array $data): self
    {
        return static::query()->create($data);
    }

    public static function latestForFile(int $fileId): ?self
    {
        return static::query()
            ->where('file_id', $fileId)
            ->orderByDesc('version_number')
            ->first();
    }

    /**
     * @return Collection<int, FileVersion>
     */
    public static function allForFile(int $fileId): Collection
    {
        return static::where('file_id', $fileId)
            ->orderByDesc('version_number')
            ->get();
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
