<?php

namespace App\Features;

use App\Models\File;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class DeleteFileFeature
{
    public function __construct(
        private FileRepositoryInterface $files,
        private UserRepositoryInterface $users,
    ) {}

    public function handle(File $file): void
    {
        $size = $file->currentVersion?->size ?? 0;

        $this->files->softDelete($file->id);

        if ($size > 0) {
            $this->users->decrementStorageUsed($file->user_id, $size);
        }
    }
}
