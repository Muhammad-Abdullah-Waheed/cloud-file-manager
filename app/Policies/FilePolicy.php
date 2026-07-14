<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;

class FilePolicy
{
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
        return $user->id === $file->user_id;
    }

    public function restore(User $user, File $file): bool
    {
        return $user->id === $file->user_id
        || $user->hasPermission('delete-any-file');
    }

    public function forceDelete(User $user, File $file): bool
    {
        return $user->id === $file->user_id
        || $user->hasPermission('delete-any-file');
    }

}
