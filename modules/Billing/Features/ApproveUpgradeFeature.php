<?php

namespace Modules\Billing\Features;

use Modules\Billing\Models\UpgradeRequest;
use Modules\Auth\Models\User;
use Modules\Billing\Notifications\UpgradeApprovedNotification;
use Modules\Billing\Repositories\Interfaces\UpgradeRequestRepositoryInterface;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;

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
