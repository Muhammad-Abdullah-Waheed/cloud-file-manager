<?php

namespace App\Repositories\Interfaces;

use App\Models\Folder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends BaseRepositoryInterface<Folder>
 */
interface FolderRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return Collection<int, Folder>
     */
    public function getRootFolders(int $userId): Collection;

    /**
     * @return Collection<int, Folder>
     */
    public function getChildren(int $folderId): Collection;

    /**
     * @return array<int, Folder>
     */
    public function getAncestors(Folder $folder): array;
}
