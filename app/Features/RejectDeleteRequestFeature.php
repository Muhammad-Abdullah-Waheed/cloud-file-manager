<?php

namespace App\Features;

use App\Models\DeleteRequest;
use App\Models\User;
use App\Notifications\DeletionRejectedNotification;
use App\Repositories\Interfaces\DeleteRequestRepositoryInterface;

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