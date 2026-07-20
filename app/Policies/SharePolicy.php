<?php

namespace App\Policies;

use App\Models\Shared;
use App\Models\User;

class SharePolicy
{
    // Only sender can revoke or update
    public function manage(User $user, Shared $share): bool
    {
        return $user->id === $share->sender_id;
    }

    // Only receiver can view what was shared with them
    public function view(User $user, Shared $share): bool
    {
        return $user->id === $share->receiver_id;
    }

    public function create(User $user): bool
    {
        return $user->id === $share->sender_id;
    }
}