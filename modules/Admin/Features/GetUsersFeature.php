<?php

namespace Modules\Admin\Features;

use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class GetUsersFeature
{
    public function __construct(private UserRepositoryInterface $users) {}

    public function handle(array $filters): LengthAwarePaginator
    {
        return $this->users->search($filters['search'] ?? null);
    }
}