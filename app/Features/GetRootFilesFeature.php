<?php

namespace App\Features;

use App\Repositories\Interfaces\FileRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GetRootFilesFeature
{
    public function __construct(
        private FileRepositoryInterface $files,
    ) {}

    public function handle(int $userId): Collection
    {
        return $this->files->getFilesInFolder(null, $userId);
    }
}
