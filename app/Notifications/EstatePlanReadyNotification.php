<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstatePlanReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $documentName
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
            ->subject('Your Estate Plan is Ready - Harbor Law')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Great news! Your completed estate planning documents are now ready for review.')
            ->line('**Document:** ' . $this->documentName)
            ->line('Your estate plan has been carefully prepared by our legal team and is now available in your client portal.')
            ->action('View Your Estate Plan', url('/dashboard'))
            ->line('**Next Steps:**')
            ->line('1. Review your documents carefully')
            ->line('2. Contact us if you have any questions')
            ->line('3. Schedule an appointment to finalize and execute your documents')
            ->line('Thank you for trusting Harbor Law with your estate planning needs.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'document_name' => $this->documentName,
        ];
    }
}
