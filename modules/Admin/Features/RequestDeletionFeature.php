<?php

namespace Modules\Admin\Features;

use Modules\Admin\Models\DeleteRequest;
use Modules\Auth\Models\User;
use Modules\Admin\Notifications\DeletionRequestedNotification;
use Modules\Admin\Repositories\Interfaces\DeleteRequestRepositoryInterface;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class RequestDeletionFeature
{
    public function __construct(
        private DeleteRequestRepositoryInterface $deleteRequests,
        private UserRepositoryInterface $users,
    ) {}

    /**
     * Returns false if a pending request already exists for this target.
     */
    public function handle(User $requester, Model $target, string $reason): bool
    {
        $targetType = $target->getMorphClass();

        if ($this->deleteRequests->hasPendingFor($requester->id, $targetType, $target->id)) {
            return false;
        }

        $deleteRequest = $this->deleteRequests->create([
            'requester_id' => $requester->id,
            'target_type'  => $targetType,
            'target_id'    => $target->id,
            'reason'       => $reason,
        ]);

        $admins = $this->users->getAdmins();

        foreach ($admins as $admin) {
            $admin->notify(new DeletionRequestedNotification($deleteRequest, $requester, $target));
        }

        return true;
    }
}