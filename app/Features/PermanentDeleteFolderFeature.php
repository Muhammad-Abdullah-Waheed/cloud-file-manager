<?php

namespace App\Features;

use App\Models\Folder;
use App\Repositories\Interfaces\FolderRepositoryInterface;
class PermanentDeleteFolderFeature
{
    public function __construct(
        private FolderRepositoryInterface $folders,
    ) {}

    public function handle(Folder $folder): void
    {
        $this->folders->permanentDelete($folder->id);
    }
}