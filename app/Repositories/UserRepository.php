<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function incrementStorageUsed(int $userId, int $bytes): void
    {
        User::where('id', $userId)->increment('storage_used', $bytes);
    }

    public function decrementStorageUsed(int $userId, int $bytes): void
    {
        User::where('id', $userId)->decrement('storage_used', $bytes);
    }
}
