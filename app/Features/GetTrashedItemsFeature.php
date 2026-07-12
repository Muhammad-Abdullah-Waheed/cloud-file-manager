<?php

namespace App\Features;

use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\FolderRepositoryInterface;
use Illuminate\Support\Collection;

class GetTrashedItemsFeature
{
    public function __construct(
        private FileRepositoryInterface $files,
        private FolderRepositoryInterface $folders,
    ) {}

    public function handle(int $userId): array
    {
        return [
            'files'   => $this->files->getTrashed($userId),
            'folders' => $this->folders->getTrashed($userId),
        ];
    }
}