<?php

namespace App\Repositories\Interfaces;

use App\Models\UpgradeRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface UpgradeRequestRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): UpgradeRequest;

    public function hasPendingFor(int $requesterId): bool;

    public function approve(UpgradeRequest $request, int $adminId): void;

    public function reject(UpgradeRequest $request, int $adminId): void;

    /**
     * @return LengthAwarePaginator<int, UpgradeRequest>
     */
    public function getPendingPaginated(int $perPage = 20): LengthAwarePaginator;
}
