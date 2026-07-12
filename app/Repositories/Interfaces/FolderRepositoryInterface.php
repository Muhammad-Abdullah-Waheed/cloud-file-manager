<?php

namespace App\Repositories\Interfaces;

use App\Models\Folder;
use Illuminate\Database\Eloquent\Collection;

interface FolderRepositoryInterface
{
    public function create(array $data): Folder;

    public function findById(int $id): ?Folder;

    public function getRootFolders(int $userId): Collection;

    public function getChildren(int $folderId): Collection;

    public function rename(int $folderId, string $name): void;

    public function softDelete(int $folderId): void;

    public function restore(int $folderId): void;

    public function getTrashed(int $userId): Collection;

    public function permanentDelete(int $folderId): void;
}
