<?php

namespace Modules\Drive\Features;

use Modules\Drive\Models\Folder;
use Modules\Drive\Repositories\Interfaces\FileRepositoryInterface;
use Modules\Drive\Repositories\Interfaces\FolderRepositoryInterface;

class GetFolderContentsFeature
{
    public function __construct(
        private FolderRepositoryInterface $folders,
        private FileRepositoryInterface $files,
    ) {}

    public function handle(Folder $folder, int $userId): array
    {
        return [
            'folders'   => $this->folders->getChildren($folder->id),
            'files'     => $this->files->getFilesInFolder($folder->id, $userId),
            'ancestors' => $this->folders->getAncestors($folder),
        ];
    }
}