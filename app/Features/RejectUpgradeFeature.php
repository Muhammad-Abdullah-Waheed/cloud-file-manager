<?php

namespace App\Features;

use App\Models\UpgradeRequest;
use App\Models\User;
use App\Repositories\Interfaces\UpgradeRequestRepositoryInterface;

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
