<?php

namespace Modules\Admin\Features;

use Modules\Admin\Models\DeleteRequest;
use Modules\Auth\Models\User;
use Modules\Admin\Notifications\DeletionRejectedNotification;
use Modules\Admin\Repositories\Interfaces\DeleteRequestRepositoryInterface;

class RejectDeleteRequestFeature
{
    public function __construct(
        private DeleteRequestRepositoryInterface $deleteRequests,
    ) {}

    public function handle(DeleteRequest $deleteRequest, User $admin): void
    {
        $this->deleteRequests->reject($deleteRequest, $admin->id);

        $deleteRequest->requester->notify(
            new DeletionRejectedNotification($deleteRequest, $admin)
        );
    }
}