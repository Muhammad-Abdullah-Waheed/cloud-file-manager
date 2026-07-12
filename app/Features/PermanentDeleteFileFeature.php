<?php

namespace App\Features;

use App\Models\File;
use App\Repositories\Interfaces\FileRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class PermanentDeleteFileFeature
{
    public function __construct(
        private FileRepositoryInterface $files,
    ) {}

    public function handle(File $file): void
    {
        foreach ($file->versions as $version) {
            if (Storage::exists($version->path)) {
                Storage::delete($version->path);
            }
        }

        $this->files->permanentDelete($file->id);
    }
}