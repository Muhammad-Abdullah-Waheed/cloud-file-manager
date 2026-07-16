<?php

namespace App\Features;

use App\Models\User;
use App\Repositories\Interfaces\DeleteRequestRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class GetDeleteRequestsFeature
{
    public function __construct(
        private DeleteRequestRepositoryInterface $deleteRequests,
    ) {}

    public function handle(User $user): array
    {
        if ($user->hasPermission('delete-any-file')) {
            return [
                'requests' => $this->deleteRequests->getPendingPaginated(20),
                'viewMode' => 'admin',
            ];
        }

        return [
            'requests' => $this->deleteRequests->getHistoryForUser($user->id, 20),
            'viewMode' => 'manager',
        ];
    }
}