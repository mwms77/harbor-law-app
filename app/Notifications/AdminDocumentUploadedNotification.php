<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\ClientUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminDocumentUploadedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public User $client,
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
        $totalUploads = $this->client->uploads()->count();
        
        return (new MailMessage)
            ->subject('New Client Document Upload - ' . $this->client->name)
            ->greeting('New Document Upload')
            ->line('**Client:** ' . $this->client->name . ' (' . $this->client->email . ')')
            ->line('**Files uploaded:** ' . $this->fileCount)
            ->line('**Category:** ' . $categoryName)
            ->line('**Total files from this client:** ' . $totalUploads)
            ->line('**Upload date:** ' . now()->format('F j, Y \a\t g:i A'))
            ->action('View Client Uploads', url('/admin/uploads/user/' . $this->client->id))
            ->line('You can review and download the uploaded documents from the admin panel.');
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
            'file_count' => $this->fileCount,
            'category' => $this->category,
        ];
    }
}
