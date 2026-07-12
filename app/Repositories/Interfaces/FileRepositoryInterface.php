<?php

namespace App\Repositories\Interfaces;

use App\Models\File;
use Illuminate\Database\Eloquent\Collection;

interface FileRepositoryInterface
{
    public function create(array $data): File;
    public function findById(int $id): ?File;
    public function getFilesInFolder(?int $folderId, int $userId): Collection;
    public function softDelete(int $fileId): void;
    public function restore(int $fileId): void;
    public function rename(int $fileId, string $name): void;
}