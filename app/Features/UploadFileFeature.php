<?php

namespace App\Features;

use App\Models\File;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\FileVersionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadFileFeature
{
    public function __construct(
        private FileRepositoryInterface $files,
        private FileVersionRepositoryInterface $versions,
        private UserRepositoryInterface $users,
    ) {}

    public function handle(UploadedFile $uploadedFile, int $userId, ?int $parentId): File
    {
        $user = $this->users->findById($userId);

        $fileSize = $uploadedFile->getSize();

        // Storage quota check
        if (($user->storage_used + $fileSize) > $user->storage_limit) {
            throw new \Exception(__('file.quota_exceeded'));
        }

        return DB::transaction(function () use ($uploadedFile, $userId, $parentId, $fileSize) {
            // Store physical file
            $path = Storage::putFile(
                'files/'.$userId,
                $uploadedFile
            );

            // Create file record
            $file = $this->files->create([
                'name' => $uploadedFile->getClientOriginalName(),
                'user_id' => $userId,
                'parent_id' => $parentId,
                'mime_type' => $uploadedFile->getMimeType(),
            ]);

            // Create first version
            $this->versions->createVersion([
                'file_id' => $file->id,
                'path' => $path,
                'size' => $fileSize,
                'version_number' => 1,
                'user_id' => $userId,
            ]);

            // Increment storage used
            $this->users->incrementStorageUsed($userId, $fileSize);

            return $file;
        });
    }
}
