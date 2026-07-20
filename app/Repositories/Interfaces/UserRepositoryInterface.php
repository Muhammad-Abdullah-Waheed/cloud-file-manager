<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function findByEmail(string $email): ?User;

    public function findById(int $id): ?User;

    public function search(?string $term, int $perPage = 20): LengthAwarePaginator;

    public function incrementStorageUsed(int $userId, int $bytes): void;

    public function decrementStorageUsed(int $userId, int $bytes): void;

    public function upgradeToPremium(int $userId): void;

    public function getAdmins(): Collection;
}
