<?php

namespace Modules\Drive\Repositories;

use App\Repositories\AbstractRepository;

use Modules\Drive\Models\FileVersion;
use Modules\Drive\Repositories\Interfaces\FileVersionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FileVersionRepository implements FileVersionRepositoryInterface
{
    public function createVersion(array $data): FileVersion
    {
        return FileVersion::storeVersion($data);
    }

    public function getLatestVersion(int $fileId): ?FileVersion
    {
        return FileVersion::latestForFile($fileId);
    }

    public function getAllVersions(int $fileId): Collection
    {
        return FileVersion::allForFile($fileId);
    }
}
