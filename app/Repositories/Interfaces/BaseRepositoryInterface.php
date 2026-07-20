<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 */
interface BaseRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $data
     * @return TModel
     */
    public function create(array $data);

    /**
     * @return TModel|null
     */
    public function findById(int $id);

    public function rename(int $id, string $name): void;

    public function softDelete(int $id): void;

    public function restore(int $id): void;

    public function permanentDelete(int $id): void;

    /**
     * @return Collection<int, TModel>
     */
    public function getTrashed(int $userId): Collection;
}
