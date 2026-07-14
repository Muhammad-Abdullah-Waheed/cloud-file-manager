<?php

namespace App\Features;

use App\Repositories\Interfaces\ShareRepositoryInterface;

class UpdateSharePermissionFeature
{
    public function __construct(
        private ShareRepositoryInterface $shares,
    ) {}

    public function handle(int $shareId, string $permission): void
    {
        $this->shares->updatePermission($shareId, $permission);
    }
}