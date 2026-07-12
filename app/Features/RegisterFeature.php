<?php

namespace App\Features;

use App\Models\User;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterFeature
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepositoryInterface $roleRepository
    ) {}

    public function handle(array $data): User
    {
        return DB::transaction(function() use ($data) {
            $role = $this->roleRepository->findByName('user');
            if (!$role) {
                throw new \Exception('Role not found');
            }
            return $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role_id' => $role->id,
            ]);
        },3);
    }
}