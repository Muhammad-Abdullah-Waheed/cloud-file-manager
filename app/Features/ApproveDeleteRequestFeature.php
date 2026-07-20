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

class ApproveDeleteRequestFeature
{
    public function __construct(
        private DeleteRequestRepositoryInterface $deleteRequests,
        private FileRepositoryInterface $files,
        private FolderRepositoryInterface $folders,
    ) {}

    public function handle(DeleteRequest $deleteRequest, User $admin): void
    {
        $target = $deleteRequest->target;

        // Approving a delete request only moves the item to trash; trashed
        // items still count toward the quota until permanently deleted.
        if ($target instanceof File) {
            $this->files->softDelete($target->id);
        } elseif ($target instanceof Folder) {
            $this->folders->softDelete($target->id);
        }

        $this->deleteRequests->approve($deleteRequest, $admin->id);

        $deleteRequest->requester->notify(
            new DeletionApprovedNotification($deleteRequest, $admin)
        );
    }
}