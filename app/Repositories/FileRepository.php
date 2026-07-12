<?php

namespace App\Repositories;

use App\Models\File;
use App\Repositories\Interfaces\FileRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FileRepository implements FileRepositoryInterface
{
    public function create(array $data): File
    {
        return File::create($data);
    }

    public function findById(int $id): ?File
    {
        return File::find($id);
    }

    public function getFilesInFolder(?int $folderId, int $userId): Collection
    {
        return File::where('user_id', $userId)
        ->when($folderId === null, function ($query) {
            // If folderId is null, look for root files
            $query->whereNull('parent_id');
        }, function ($query) use ($folderId) {
            // If folderId is a number, look inside that subfolder
            $query->where('parent_id', $folderId);
        })
        ->with('currentVersion')
        ->get();
    }

    public function softDelete(int $fileId): void
    {
        File::findOrFail($fileId)->delete();
    }

    public function restore(int $fileId): void
    {
        File::withTrashed()->findOrFail($fileId)->restore();
    }

    public function rename(int $fileId, string $name): void
    {
        File::where('id', $fileId)->update(['name' => $name]);
    }

    public function getTrashed(int $userId): Collection
    {
        return File::onlyTrashed()
            ->where('user_id', $userId)
            ->with('currentVersion')
            ->get();
    }

    public function permanentDelete(int $fileId): void
    {
        File::withTrashed()->findOrFail($fileId)->forceDelete();
    }
}