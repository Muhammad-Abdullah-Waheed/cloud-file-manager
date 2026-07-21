<?php

namespace Modules\Sharing\Features;

use Modules\Sharing\Models\Shared;
use Modules\Sharing\Notifications\FileSharedNotification;
use Modules\Sharing\Repositories\Interfaces\ShareRepositoryInterface;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;

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
            throw new \Exception(__('sharing::share.cannot_share_with_self'));
        }

        // Prevent duplicate shares
        $existing = $this->shares->findShare(
            $senderId,
            $receiver->id,
            $data['shared_type'],
            $data['shared_id']
        );

        if ($existing) {
            throw new \Exception(__('sharing::share.already_shared'));
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