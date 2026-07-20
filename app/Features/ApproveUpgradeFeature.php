<?php

namespace App\Features;

use App\Models\UpgradeRequest;
use App\Models\User;
use App\Notifications\UpgradeApprovedNotification;
use App\Repositories\Interfaces\UpgradeRequestRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class ApproveUpgradeFeature
{
    public function __construct(
        private UpgradeRequestRepositoryInterface $requests,
        private UserRepositoryInterface $users,
    ) {}

    public function handle(UpgradeRequest $request, User $admin): void
    {
        $this->users->upgradeToPremium($request->requester_id);
        $this->requests->approve($request, $admin->id);

        $request->requester->notify(new UpgradeApprovedNotification);
    }
}
