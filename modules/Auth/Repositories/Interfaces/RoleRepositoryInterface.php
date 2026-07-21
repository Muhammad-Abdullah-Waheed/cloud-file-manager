<?php

namespace Modules\Auth\Repositories\Interfaces;

use Modules\Auth\Models\Role;

interface RoleRepositoryInterface
{
    public function findByName(string $name): ?Role;
}
