<?php

namespace Modules\Sharing\Notifications;

use Modules\Sharing\Models\Shared;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FileSharedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Shared $share)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->markdown('mail.file-shared-notification');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $type = $this->share->shared_type === 'file' ? 'file' : 'folder';

        return [
            'message' => __('notifications.shared_with_you', [
                'type' => $type,
                'name' => $this->share->shared->name ?? '',
            ]),
            'shared_type' => $this->share->shared_type,
            'shared_id' => $this->share->shared_id,
            'sender_id' => $this->share->sender_id,
            'permission' => $this->share->permission,
        ];
    }
}
