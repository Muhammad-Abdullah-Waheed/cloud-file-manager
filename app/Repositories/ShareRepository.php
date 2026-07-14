<?php

namespace App\Repositories;

use App\Models\Shared;
use App\Repositories\Interfaces\ShareRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ShareRepository implements ShareRepositoryInterface
{
    public function create(array $data): Shared
    {
        return Shared::create($data);
    }

    public function findShare(int $senderId, int $receiverId, string $type, int $id): ?Shared
    {
        return Shared::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->where('shared_type', $type)
            ->where('shared_id', $id)
            ->first();
    }

    public function getSharesForUser(int $userId): Collection
    {
        return Shared::where('receiver_id', $userId)
            ->with('sender')
            ->get();
    }

    public function updatePermission(int $shareId, string $permission): void
    {
        Shared::where('id', $shareId)->update(['permission' => $permission]);
    }

    public function revoke(int $shareId): void
    {
        Shared::findOrFail($shareId)->delete();
    }

    public function findById(int $shareId): ?Shared
    {
        return Shared::find($shareId);
    }
}