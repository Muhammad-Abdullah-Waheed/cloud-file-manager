<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function findByName(string $name): ?Role
    {
        return Role::findByName($name);
    }
}
