<?php

namespace App\Features;

use App\Repositories\Interfaces\FolderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GetRootFoldersFeature
{
    public function __construct(
        private FolderRepositoryInterface $folders,
    ) {}

    public function handle(int $userId): Collection
    {
        return $this->folders->getRootFolders($userId);
    }
}