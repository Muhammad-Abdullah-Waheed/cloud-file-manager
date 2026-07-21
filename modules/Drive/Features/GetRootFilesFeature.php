<?php

namespace Modules\Drive\Features;

use Modules\Drive\Repositories\Interfaces\FileRepositoryInterface;
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
