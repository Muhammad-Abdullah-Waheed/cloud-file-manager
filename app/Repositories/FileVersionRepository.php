<?php

namespace App\Repositories;

use App\Models\FileVersion;
use App\Repositories\Interfaces\FileVersionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FileVersionRepository implements FileVersionRepositoryInterface
{
    public function createVersion(array $data): FileVersion
    {
        return FileVersion::create($data);
    }

    public function getLatestVersion(int $fileId): ?FileVersion
    {
        return FileVersion::where('file_id', $fileId)
            ->orderByDesc('version_number')
            ->first();
    }

    public function getAllVersions(int $fileId): Collection
    {
        return FileVersion::where('file_id', $fileId)
            ->orderByDesc('version_number')
            ->get();
    }
}
