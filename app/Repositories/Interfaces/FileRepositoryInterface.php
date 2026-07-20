<?php

namespace App\Repositories\Interfaces;

use App\Models\File;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends BaseRepositoryInterface<File>
 */
interface FileRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return Collection<int, File>
     */
    public function getFilesInFolder(?int $folderId, int $userId): Collection;
}
