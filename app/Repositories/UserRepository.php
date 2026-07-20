<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::findByEmailAddress($email);
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function search(?string $term, int $perPage = 20): LengthAwarePaginator
    {
        return User::searchByTerm($term, $perPage);
    }

    public function incrementStorageUsed(int $userId, int $bytes): void
    {
        User::addStorageUsed($userId, $bytes);
    }

    public function decrementStorageUsed(int $userId, int $bytes): void
    {
        User::removeStorageUsed($userId, $bytes);
    }

    public function getAdmins(): Collection
    {
        return User::admins();
    }
}
