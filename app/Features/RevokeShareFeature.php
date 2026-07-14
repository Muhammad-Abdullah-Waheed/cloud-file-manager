<?php

namespace App\Features;

use App\Repositories\Interfaces\ShareRepositoryInterface;

class RevokeShareFeature
{
    public function __construct(
        private ShareRepositoryInterface $shares,
    ) {}

    public function handle(int $shareId): void
    {
        $this->shares->revoke($shareId);
    }
}