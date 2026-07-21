<?php

namespace Modules\Admin\Features;

use Modules\Admin\Models\DeleteRequest;
use Modules\Auth\Models\User;
use Modules\Admin\Notifications\DeletionApprovedNotification;
use Modules\Admin\Repositories\Interfaces\DeleteRequestRepositoryInterface;
use Modules\Drive\Models\File;
use Modules\Drive\Models\Folder;
use Modules\Drive\Repositories\Interfaces\FileRepositoryInterface;
use Modules\Drive\Repositories\Interfaces\FolderRepositoryInterface;

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