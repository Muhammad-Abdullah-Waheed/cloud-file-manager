<?php

namespace App\Features;

use App\Models\File;
use App\Repositories\Interfaces\FileRepositoryInterface;

class RestoreFileFeature
{
    public function __construct(
        private FileRepositoryInterface $files,
    ) {}

    public function handle(File $file): void
    {
        $this->files->restore($file->id);
    }
}