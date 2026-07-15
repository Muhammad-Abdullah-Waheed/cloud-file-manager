<?php

namespace App\Features;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class GetUsersFeature
{
    public function __construct(private UserRepositoryInterface $users) {}

    public function handle(array $filters): LengthAwarePaginator
    {
        return $this->users->search($filters['search'] ?? null);
    }
}