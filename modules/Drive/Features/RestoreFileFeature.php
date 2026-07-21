<?php

namespace Modules\Drive\Features;

use Modules\Drive\Models\File;
use Modules\Drive\Repositories\Interfaces\FileRepositoryInterface;

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
