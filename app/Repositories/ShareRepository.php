<?php

namespace App\Repositories;

use App\Models\Shared;
use App\Repositories\Interfaces\ShareRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ShareRepository implements ShareRepositoryInterface
{
    public function create(array $data): Shared
    {
        return Shared::storeShare($data);
    }

    public function findShare(int $senderId, int $receiverId, string $type, int $id): ?Shared
    {
        return Shared::findShareRecord($senderId, $receiverId, $type, $id);
    }

    public function getSharesForUser(int $userId): Collection
    {
        return Shared::forReceiver($userId);
    }

    public function updatePermission(int $shareId, string $permission): void
    {
        Shared::updatePermissionById($shareId, $permission);
    }

    public function revoke(int $shareId): void
    {
        Shared::revokeById($shareId);
    }

    public function findById(int $shareId): ?Shared
    {
        return Shared::findRecordById($shareId);
    }
}
