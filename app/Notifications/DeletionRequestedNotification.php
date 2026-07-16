<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Mail\DeletionRequestedMail;
use App\Models\DeleteRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class DeletionRequestedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public DeleteRequest $deleteRequest,
        public User $requester,
        public Model $target,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return new DeletionRequestedMail($this->deleteRequest, $this->requester, $this->target);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'delete_request_id' => $this->deleteRequest->id,
            'requester_name'    => $this->requester->name,
            'target_type'       => $this->deleteRequest->target_type,
            'target_name'       => $this->target->name,
            'reason'            => $this->deleteRequest->reason,
            'message'           => "{$this->requester->name} requested to delete {$this->deleteRequest->target_type} \"{$this->target->name}\".",
        ];
    }
}
