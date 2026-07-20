<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface HasRecordQueries
{
    /**
     * @param  array<string, mixed>  $data
     * @return static
     */
    public static function storeRecord(array $data): static;

    /**
     * @return static|null
     */
    public static function findRecord(int $id): ?static;

    public static function renameRecord(int $id, string $name): void;

    public static function trashRecord(int $id): void;

    public static function restoreRecord(int $id): void;

    public static function purgeRecord(int $id): void;

    /**
     * @return Collection<int, static&Model>
     */
    public static function trashedForUser(int $userId): Collection;
}
