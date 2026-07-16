<?php

namespace App\Features;

use App\Models\DeleteRequest;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use App\Notifications\DeletionApprovedNotification;
use App\Repositories\Interfaces\DeleteRequestRepositoryInterface;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\FolderRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class ApproveDeleteRequestFeature
{
    public function __construct(
        private DeleteRequestRepositoryInterface $deleteRequests,
        private FileRepositoryInterface $files,
        private FolderRepositoryInterface $folders,
        private UserRepositoryInterface $users,
    ) {}

    public function handle(DeleteRequest $deleteRequest, User $admin): void
    {
        $target = $deleteRequest->target;

        if ($target instanceof File) {
            $size = $target->currentVersion?->size ?? 0;
            $this->files->softDelete($target->id);
            if ($size > 0) {
                $this->users->decrementStorageUsed($target->user_id, $size);
            }
        } elseif ($target instanceof Folder) {
            $this->folders->softDelete($target->id);
        }

        $this->deleteRequests->approve($deleteRequest, $admin->id);

        $deleteRequest->requester->notify(
            new DeletionApprovedNotification($deleteRequest, $admin)
        );
    }
}