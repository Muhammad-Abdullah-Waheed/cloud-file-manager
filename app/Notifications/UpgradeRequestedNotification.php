<?php

namespace App\Notifications;

use App\Models\UpgradeRequest;
use App\Models\User;
use Illuminate\Notifications\Notification;

class UpgradeRequestedNotification extends Notification
{
    public function __construct(
        private UpgradeRequest $request,
        private User $requester,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'message'    => __('upgrade.notify_admin', ['name' => $this->requester->name]),
            'request_id' => $this->request->id,
            'type'       => 'upgrade_request',
        ];
    }
}
