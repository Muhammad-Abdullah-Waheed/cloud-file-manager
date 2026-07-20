<?php

namespace App\Repositories;

use App\Models\Contracts\HasRecordQueries;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model&HasRecordQueries
 */
abstract class AbstractRepository
{
    /**
     * The Eloquent model this repository manages.
     *
     * @return class-string<TModel>
     */
    abstract protected function model(): string;

    /**
     * @param  array<string, mixed>  $data
     * @return TModel
     */
    public function create(array $data)
    {
        return ($this->model())::storeRecord($data);
    }

    /**
     * @return TModel|null
     */
    public function findById(int $id)
    {
        return ($this->model())::findRecord($id);
    }

    public function rename(int $id, string $name): void
    {
        ($this->model())::renameRecord($id, $name);
    }

    public function softDelete(int $id): void
    {
        ($this->model())::trashRecord($id);
    }

    public function restore(int $id): void
    {
        ($this->model())::restoreRecord($id);
    }

    public function permanentDelete(int $id): void
    {
        ($this->model())::purgeRecord($id);
    }

    /**
     * @return Collection<int, TModel>
     */
    public function getTrashed(int $userId): Collection
    {
        return ($this->model())::trashedForUser($userId);
    }
}
