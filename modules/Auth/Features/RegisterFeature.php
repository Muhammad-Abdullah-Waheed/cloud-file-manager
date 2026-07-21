<?php

namespace Modules\Auth\Features;

use Modules\Auth\Models\User;
use Modules\Auth\Repositories\Interfaces\RoleRepositoryInterface;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RegisterFeature
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepositoryInterface $roleRepository
    ) {}

    public function handle(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $role = $this->roleRepository->findByName('user');
            if (! $role) {
                throw new \Exception('Role not found');
            }

            return $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role_id' => $role->id,
            ]);
        }, 3);
    }
}
