<?php

namespace App\Repositories\Interfaces;

use App\Models\DeleteRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface DeleteRequestRepositoryInterface
{
    public function create(array $data): DeleteRequest;

    public function hasPendingFor(int $requesterId, string $targetType, int $targetId): bool;

    public function approve(DeleteRequest $deleteRequest, int $adminId): void;

    public function reject(DeleteRequest $deleteRequest, int $adminId): void;

    public function getPendingPaginated(int $perPage = 20): LengthAwarePaginator;

    public function getHistoryForUser(int $userId, int $perPage = 20): LengthAwarePaginator;
}