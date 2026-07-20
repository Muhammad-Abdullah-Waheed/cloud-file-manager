<?php

namespace App\Policies;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FolderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Folder $folder): bool
    {
        return $user->id === $folder->user_id
        || $user->hasPermission('view-all-files');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Folder $folder): bool
    {
        return $user->id === $folder->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Folder $folder): bool
    {
        return $user->id === $folder->user_id
        || $user->hasPermission('delete-any-file');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Folder $folder): bool
    {
        return $user->id === $folder->user_id
        || $user->hasPermission('delete-any-file');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Folder $folder): Response|bool
    {
        $owns = $user->id === $folder->user_id;

        if (! $owns && ! $user->hasPermission('delete-any-file')) {
            return false;
        }

        // Admins/managers with global delete rights bypass the retention window.
        if ($user->hasPermission('delete-any-file')) {
            return true;
        }

        $days = (int) config('storage.trash_retention_days', 2);
        $availableAt = $folder->deleted_at?->addDays($days);

        if ($availableAt && $availableAt->isFuture()) {
            return Response::deny(__('trash.retention_locked', [
                'time' => $availableAt->diffForHumans(),
            ]));
        }

        return true;
    }
}
