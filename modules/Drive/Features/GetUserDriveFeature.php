<?php

namespace Modules\Drive\Features;

use Modules\Drive\Repositories\Interfaces\FileRepositoryInterface;
use Modules\Drive\Repositories\Interfaces\FolderRepositoryInterface;

class GetUserDriveFeature
{
    public function __construct(
        private FolderRepositoryInterface $folders,
        private FileRepositoryInterface $files,
    ) {}

    public function handle(int $userId, ?int $folderId): array
    {
        if ($folderId === null) {
            return [
                'folders'   => $this->folders->getRootFolders($userId),
                'files'     => $this->files->getFilesInFolder(null, $userId),
                'ancestors' => [],
            ];
        }

        $folder = $this->folders->findById($folderId);

        return [
            'folders'   => $this->folders->getChildren($folderId),
            'files'     => $this->files->getFilesInFolder($folderId, $userId),
            'ancestors' => $this->folders->getAncestors($folder),
        ];
    }
}