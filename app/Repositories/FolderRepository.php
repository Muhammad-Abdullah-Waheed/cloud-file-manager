<?php

namespace App\Repositories;

use App\Models\Folder;
use App\Repositories\Interfaces\FolderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class FolderRepository implements FolderRepositoryInterface
{
    public function create(array $data): Folder
    {
        return Folder::create($data);
    }

    public function findById(int $id): ?Folder
    {
        return Folder::find($id);
    }

    public function getRootFolders(int $userId): Collection
    {
        return Folder::where('user_id', $userId)
            ->whereNull('parent_id')
            ->get();
    }

    public function getChildren(int $folderId): Collection
    {
        return Folder::where('parent_id', $folderId)->get();
    }

    public function rename(int $folderId, string $name): void
    {
        Folder::where('id', $folderId)->update([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }

    public function softDelete(int $folderId): void
    {
        Folder::findOrFail($folderId)->delete();
    }

    public function restore(int $folderId): void
    {
        Folder::withTrashed()->findOrFail($folderId)->restore();
    }

    public function getTrashed(int $userId): Collection
    {
        return Folder::onlyTrashed()
            ->where('user_id', $userId)
            ->get();
    }

    public function permanentDelete(int $folderId): void
    {
        Folder::withTrashed()->findOrFail($folderId)->forceDelete();
    }
}
