<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FilePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, File $file): bool
    {
        return $user->id === $file->user_id
        || $user->hasPermission('view-all-files');
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, File $file): bool
    {
        return $user->id === $file->user_id
        || $user->hasPermission('delete-any-file');
    }

    public function download(User $user, File $file): bool
    {
        return $user->id === $file->user_id
            || $user->hasPermission('view-all-files');
    }

    public function restore(User $user, File $file): bool
    {
        return $user->id === $file->user_id
        || $user->hasPermission('delete-any-file');
    }

    public function forceDelete(User $user, File $file): Response|bool
    {
        $owns = $user->id === $file->user_id;

        if (! $owns && ! $user->hasPermission('delete-any-file')) {
            return false;
        }

        // Admins/managers with global delete rights bypass the retention window.
        if ($user->hasPermission('delete-any-file')) {
            return true;
        }

        $days = (int) config('storage.trash_retention_days', 2);
        $availableAt = $file->deleted_at?->addDays($days);

        if ($availableAt && $availableAt->isFuture()) {
            return Response::deny(__('trash.retention_locked', [
                'time' => $availableAt->diffForHumans(),
            ]));
        }

        return true;
    }
}
