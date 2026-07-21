<?php

namespace Modules\Drive\Features;

use Modules\Drive\Repositories\Interfaces\FolderRepositoryInterface;

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
