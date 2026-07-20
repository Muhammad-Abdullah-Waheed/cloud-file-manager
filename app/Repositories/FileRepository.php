<?php

namespace App\Repositories;

use App\Models\File;
use App\Repositories\Interfaces\FileRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends AbstractRepository<File>
 */
class FileRepository extends AbstractRepository implements FileRepositoryInterface
{
    protected function model(): string
    {
        return File::class;
    }

    public function getFilesInFolder(?int $folderId, int $userId): Collection
    {
        return File::filesInFolderForUser($folderId, $userId);
    }
}
