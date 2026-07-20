<?php

namespace App\Features;

use App\Models\File;
use App\Repositories\Interfaces\FileRepositoryInterface;

class DeleteFileFeature
{
    public function __construct(
        private FileRepositoryInterface $files,
    ) {}

    public function handle(File $file): void
    {
        // Trashed files still count toward the quota, so we do NOT release
        // storage here. Space is only freed on permanent delete.
        $this->files->softDelete($file->id);
    }
}
