<?php

namespace App\Repositories;

use App\Models\DeleteRequest;
use App\Repositories\Interfaces\DeleteRequestRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class DeleteRequestRepository implements DeleteRequestRepositoryInterface
{
    public function create(array $data): DeleteRequest
    {
        return DeleteRequest::storeRequest($data);
    }

    public function hasPendingFor(int $requesterId, string $targetType, int $targetId): bool
    {
        return DeleteRequest::existsPendingFor($requesterId, $targetType, $targetId);
    }

    public function approve(DeleteRequest $deleteRequest, int $adminId): void
    {
        $deleteRequest->markApproved($adminId);
    }

    public function reject(DeleteRequest $deleteRequest, int $adminId): void
    {
        $deleteRequest->markRejected($adminId);
    }

    public function getPendingPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return DeleteRequest::pendingPaginated($perPage);
    }

    public function getHistoryForUser(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return DeleteRequest::historyForUser($userId, $perPage);
    }
}
