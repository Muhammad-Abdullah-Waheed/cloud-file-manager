<?php

namespace Modules\Auth\Repositories;

use Modules\Auth\Models\Role;
use Modules\Auth\Repositories\Interfaces\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function findByName(string $name): ?Role
    {
        return Role::findByName($name);
    }
}
