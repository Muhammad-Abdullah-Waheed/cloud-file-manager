<?php

namespace Modules\Billing\Features;

use Modules\Auth\Models\User;
use Modules\Billing\Notifications\UpgradeRequestedNotification;
use Modules\Billing\Repositories\Interfaces\UpgradeRequestRepositoryInterface;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;

class RequestUpgradeFeature
{
    public function __construct(
        private UpgradeRequestRepositoryInterface $requests,
        private UserRepositoryInterface $users,
    ) {}

    /**
     * Returns false if the user is already premium or already has a pending request.
     */
    public function handle(User $requester, ?string $reason = null): bool
    {
        if ($requester->isPremium() || $this->requests->hasPendingFor($requester->id)) {
            return false;
        }

        $request = $this->requests->create([
            'requester_id' => $requester->id,
            'reason'       => $reason,
        ]);

        foreach ($this->users->getAdmins() as $admin) {
            $admin->notify(new UpgradeRequestedNotification($request, $requester));
        }

        return true;
    }
}
