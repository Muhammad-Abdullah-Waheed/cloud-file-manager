<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Collection;

trait SoftDeletableRecord
{
    /**
     * @param  array<string, mixed>  $data
     * @return static
     */
    public static function storeRecord(array $data): static
    {
        return static::query()->create($data);
    }

    /**
     * @return static|null
     */
    public static function findRecord(int $id): ?static
    {
        return static::query()->find($id);
    }

    public static function renameRecord(int $id, string $name): void
    {
        static::query()->whereKey($id)->update(['name' => $name]);
    }

    public static function trashRecord(int $id): void
    {
        static::query()->findOrFail($id)->delete();
    }

    public static function restoreRecord(int $id): void
    {
        static::query()->withTrashed()->findOrFail($id)->restore();
    }

    public static function purgeRecord(int $id): void
    {
        static::query()->withTrashed()->findOrFail($id)->forceDelete();
    }

    /**
     * @return Collection<int, static>
     */
    public static function trashedForUser(int $userId): Collection
    {
        return static::query()
            ->onlyTrashed()
            ->where('user_id', $userId)
            ->get();
    }
}
