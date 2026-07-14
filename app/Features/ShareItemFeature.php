<?php

namespace App\Features;

use App\Models\Shared;
use App\Notifications\FileSharedNotification;
use App\Repositories\Interfaces\ShareRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class ShareItemFeature
{
    public function __construct(
        private ShareRepositoryInterface $shares,
        private UserRepositoryInterface $users,
    ) {}

    public function handle(array $data, int $senderId): Shared
    {
        $receiver = $this->users->findByEmail($data['receiver_email']);

        // Prevent sharing with yourself
        if ($receiver->id === $senderId) {
            throw new \Exception(__('share.cannot_share_with_self'));
        }

        // Prevent duplicate shares
        $existing = $this->shares->findShare(
            $senderId,
            $receiver->id,
            $data['shared_type'],
            $data['shared_id']
        );

        if ($existing) {
            throw new \Exception(__('share.already_shared'));
        }

        $share = $this->shares->create([
            'sender_id'   => $senderId,
            'receiver_id' => $receiver->id,
            'permission'  => $data['permission'],
            'shared_type' => $data['shared_type'],
            'shared_id'   => $data['shared_id'],
        ]);

        $receiver->notify(new FileSharedNotification($share));

        return $share;
    }
}