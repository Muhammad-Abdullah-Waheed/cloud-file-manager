<?php

namespace Modules\Billing\Repositories;

use Modules\Billing\Models\UpgradeRequest;
use Modules\Billing\Repositories\Interfaces\UpgradeRequestRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UpgradeRequestRepository implements UpgradeRequestRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): UpgradeRequest
    {
        return UpgradeRequest::storeRequest($data);
    }

    public function hasPendingFor(int $requesterId): bool
    {
        return UpgradeRequest::existsPendingFor($requesterId);
    }

    public function approve(UpgradeRequest $request, int $adminId): void
    {
        $request->markApproved($adminId);
    }

    public function reject(UpgradeRequest $request, int $adminId): void
    {
        $request->markRejected($adminId);
    }

    public function getPendingPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return UpgradeRequest::pendingPaginated($perPage);
    }
}
