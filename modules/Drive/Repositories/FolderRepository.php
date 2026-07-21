<?php

namespace Modules\Drive\Repositories;

use App\Repositories\AbstractRepository;

use Modules\Drive\Models\Folder;
use Modules\Drive\Repositories\Interfaces\FolderRepositoryInterface;
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
