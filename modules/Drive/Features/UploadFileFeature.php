<?php

namespace Modules\Drive\Features;

use Modules\Drive\Models\File;
use Modules\Drive\Repositories\Interfaces\FileRepositoryInterface;
use Modules\Drive\Repositories\Interfaces\FileVersionRepositoryInterface;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Billing\Notifications\StorageQuotaWarningNotification;
use Modules\Billing\Exceptions\StorageQuotaExceededException;

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

        // Storage quota check (active + trashed files both count toward the limit).
        if (! $user->hasStorageFor($fileSize)) {
            throw new StorageQuotaExceededException(
                tier: $user->tier,
                used: $user->storage_used,
                limit: $user->storage_limit,
                attempted: $fileSize,
            );
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
            

            $updatedUser = $this->users->findById($userId);
            $threshold = (int) config('storage.warning_threshold', 80);
            $percent = (int) round(($updatedUser->storage_used / $updatedUser->storage_limit) * 100);
            if ($percent >= $threshold) {
                $updatedUser->notify(new StorageQuotaWarningNotification($percent));
            }

            return $file;
        });
    }
}
