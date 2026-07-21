<?php

namespace Modules\Billing\Notifications;

use Modules\Billing\Models\UpgradeRequest;
use Modules\Auth\Models\User;
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
            'message'    => __('billing::upgrade.notify_admin', ['name' => $this->requester->name]),
            'request_id' => $this->request->id,
            'type'       => 'upgrade_request',
        ];
    }
}
