<?php

namespace Modules\Drive\Features;

use Modules\Drive\Repositories\Interfaces\FolderRepositoryInterface;

class RenameFolderFeature
{
    public function __construct(
        private FolderRepositoryInterface $folderRepository,
    ) {}

    public function handle(int $folderId, string $name): void
    {
        $this->folderRepository->rename($folderId, $name);
    }
}
