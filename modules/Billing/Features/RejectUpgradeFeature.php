<?php

namespace Modules\Billing\Features;

use Modules\Billing\Models\UpgradeRequest;
use Modules\Auth\Models\User;
use Modules\Billing\Repositories\Interfaces\UpgradeRequestRepositoryInterface;

class RejectUpgradeFeature
{
    public function __construct(
        private UpgradeRequestRepositoryInterface $requests,
    ) {}

    public function handle(UpgradeRequest $request, User $admin): void
    {
        $this->requests->reject($request, $admin->id);
    }
}
