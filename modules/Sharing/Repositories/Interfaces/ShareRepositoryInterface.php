<?php

namespace Modules\Sharing\Repositories\Interfaces;

use Modules\Sharing\Models\Shared;
use Illuminate\Database\Eloquent\Collection;

interface ShareRepositoryInterface
{
    public function create(array $data): Shared;
    public function findShare(int $senderId, int $receiverId, string $type, int $id): ?Shared;
    public function getSharesForUser(int $userId): Collection;
    public function updatePermission(int $shareId, string $permission): void;
    public function revoke(int $shareId): void;
    public function findById(int $shareId): ?Shared;
}