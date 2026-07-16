<?php

namespace App\Repositories;

use App\Models\DeleteRequest;
use App\Repositories\Interfaces\DeleteRequestRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class DeleteRequestRepository implements DeleteRequestRepositoryInterface
{
    public function create(array $data): DeleteRequest
    {
        return DeleteRequest::create($data);
    }

    public function hasPendingFor(int $requesterId, string $targetType, int $targetId): bool
    {
        return DeleteRequest::where('requester_id', $requesterId)
            ->where('target_type', $targetType)
            ->where('target_id', $targetId)
            ->pending()
            ->exists();
    }

    public function approve(DeleteRequest $deleteRequest, int $adminId): void
    {
        $deleteRequest->update([
            'status'      => 'approved',
            'actioned_by' => $adminId,
            'actioned_at' => now(),
        ]);
    }

    public function reject(DeleteRequest $deleteRequest, int $adminId): void
    {
        $deleteRequest->update([
            'status'      => 'rejected',
            'actioned_by' => $adminId,
            'actioned_at' => now(),
        ]);
    }

    public function getPendingPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return DeleteRequest::with(['requester', 'target', 'actionedBy'])
            ->pending()
            ->latest()
            ->paginate($perPage);
    }

    public function getHistoryForUser(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return DeleteRequest::with(['target', 'actionedBy'])
            ->where('requester_id', $userId)
            ->latest()
            ->paginate($perPage);
    }
}