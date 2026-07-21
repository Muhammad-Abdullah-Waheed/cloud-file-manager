<?php

namespace Modules\Sharing\Features;

use Modules\Sharing\Repositories\Interfaces\ShareRepositoryInterface;

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