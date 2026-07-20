<?php

namespace App\Repositories;

use App\Models\Folder;
use App\Repositories\Interfaces\FolderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends AbstractRepository<Folder>
 */
class FolderRepository extends AbstractRepository implements FolderRepositoryInterface
{
    protected function model(): string
    {
        return Folder::class;
    }

    public function getRootFolders(int $userId): Collection
    {
        return Folder::rootForUser($userId);
    }

    public function getChildren(int $folderId): Collection
    {
        return Folder::childrenOf($folderId);
    }

    public function getAncestors(Folder $folder): array
    {
        return $folder->ancestors();
    }
}
