<?php

namespace App\Features;

use App\Models\File;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class PermanentDeleteFileFeature
{
    public function __construct(
        private FileRepositoryInterface $files,
        private UserRepositoryInterface $users,
    ) {}

    public function handle(File $file): void
    {
        $released = 0;

        foreach ($file->versions as $version) {
            $released += (int) $version->size;

            if (Storage::exists($version->path)) {
                Storage::delete($version->path);
            }
        }

        $this->files->permanentDelete($file->id);

        // Space is only released once the file leaves trash permanently.
        if ($released > 0) {
            $this->users->decrementStorageUsed($file->user_id, $released);
        }
    }
}
