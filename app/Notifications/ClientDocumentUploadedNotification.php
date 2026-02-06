<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ClientUpload;

class ClientDocumentUploadedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public int $fileCount,
        public string $category
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
        $categoryName = ClientUpload::$categories[$this->category] ?? 'Documents';
        
        return (new MailMessage)
            ->subject('Document Upload Confirmation - Harbor Law')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This email confirms that your documents have been successfully uploaded to your Harbor Law estate planning portal.')
            ->line('**Upload Details:**')
            ->line('Files uploaded: ' . $this->fileCount)
            ->line('Category: ' . $categoryName)
            ->line('Upload date: ' . now()->format('F j, Y \a\t g:i A'))
            ->line('Your documents are now securely stored and available for review by our team.')
            ->action('View Your Uploads', url('/uploads'))
            ->line('If you need to upload additional documents, you can do so at any time through your client portal.')
            ->line('Thank you for choosing Harbor Law for your estate planning needs.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'file_count' => $this->fileCount,
            'category' => $this->category,
        ];
    }
}
