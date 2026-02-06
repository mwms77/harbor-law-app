<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminDocumentUploadedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $uploads;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, $uploads)
    {
        $this->user = $user;
        $this->uploads = is_array($uploads) ? $uploads : [$uploads];
    }

    /**
     * Get the notification's delivery channels.
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
        $fileCount = count($this->uploads);
        $category = $this->uploads[0]->category_name ?? 'documents';
        $url = route('admin.uploads.user', $this->user);

        return (new MailMessage)
            ->subject('Client Document Upload - ' . $this->user->full_name)
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line($this->user->full_name . ' has uploaded ' . $fileCount . ' document(s).')
            ->line('Category: ' . $category)
            ->line('Files uploaded:')
            ->lines($this->getFileList())
            ->action('View Client Uploads', $url)
            ->line('You can download and review these documents from the admin panel.')
            ->salutation('Best regards, Harbor Law Estate Planning System');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->full_name,
            'upload_count' => count($this->uploads),
            'category' => $this->uploads[0]->category ?? null,
        ];
    }

    /**
     * Get formatted file list.
     */
    protected function getFileList(): array
    {
        return array_map(function($upload) {
            return '• ' . $upload->original_name . ' (' . $upload->formatted_size . ')';
        }, $this->uploads);
    }
}
