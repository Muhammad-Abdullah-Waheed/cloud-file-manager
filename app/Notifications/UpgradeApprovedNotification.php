<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class UpgradeApprovedNotification extends Notification
{
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
            'message' => __('upgrade.notify_user_approved'),
            'type'    => 'upgrade_approved',
        ];
    }
}
