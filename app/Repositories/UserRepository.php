<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function search(?string $term, int $perPage = 20): LengthAwarePaginator
    {
        return User::with('role')
            ->when($term, fn ($q) => $q->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            }))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function incrementStorageUsed(int $userId, int $bytes): void
    {
        User::where('id', $userId)->increment('storage_used', $bytes);
    }

    public function decrementStorageUsed(int $userId, int $bytes): void
    {
        User::where('id', $userId)->decrement('storage_used', $bytes);
    }

    public function getAdmins(): Collection
    {
        return User::whereHas('role', fn ($q) => $q->where('name', 'admin'))->get();
    }
}
