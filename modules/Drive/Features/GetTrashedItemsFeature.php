<?php

namespace Modules\Drive\Features;

use Modules\Drive\Repositories\Interfaces\FileRepositoryInterface;
use Modules\Drive\Repositories\Interfaces\FolderRepositoryInterface;

class GetTrashedItemsFeature
{
    public function __construct(
        private FileRepositoryInterface $files,
        private FolderRepositoryInterface $folders,
    ) {}

    public function handle(int $userId): array
    {
        return [
            'files' => $this->files->getTrashed($userId),
            'folders' => $this->folders->getTrashed($userId),
        ];
    }
}
