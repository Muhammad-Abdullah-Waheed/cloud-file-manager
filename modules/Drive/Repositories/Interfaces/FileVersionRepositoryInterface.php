<?php

namespace Modules\Drive\Repositories\Interfaces;

use App\Repositories\Interfaces\BaseRepositoryInterface;

use Modules\Drive\Models\FileVersion;

interface FileVersionRepositoryInterface
{
    public function createVersion(array $data): FileVersion;

    public function getLatestVersion(int $fileId): ?FileVersion;
}
