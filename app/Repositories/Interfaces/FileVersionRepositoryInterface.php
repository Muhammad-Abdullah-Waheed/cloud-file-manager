<?php

namespace App\Repositories\Interfaces;

use App\Models\FileVersion;

interface FileVersionRepositoryInterface
{
    public function createVersion(array $data): FileVersion;
    public function getLatestVersion(int $fileId): ?FileVersion;
}