<?php

namespace App\Features;

use App\Repositories\Interfaces\FolderRepositoryInterface;

class DeleteFolderFeature
{
    public function __construct(
        private FolderRepositoryInterface $folders,
    ) {}

    public function handle(int $folderId): void
    {
        $this->folders->softDelete($folderId);
    }
}
