<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IntakeCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public User $client
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Client Intake Form Completed - ' . $this->client->name)
            ->greeting('Intake Form Completed')
            ->line('**Client:** ' . $this->client->name)
            ->line('**Email:** ' . $this->client->email)
            ->line('**Completion date:** ' . $this->client->intake_completed_at->format('F j, Y \a\t g:i A'))
            ->line('The client has successfully completed their estate planning intake form.')
            ->action('Review Client Information', url('/admin/users/' . $this->client->id))
            ->line('You can now review their information and begin processing their estate plan.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'client_id' => $this->client->id,
            'client_name' => $this->client->name,
            'client_email' => $this->client->email,
        ];
    }
}
