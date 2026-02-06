<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientDocumentUploadedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $uploads;

    /**
     * Create a new notification instance.
     */
    public function __construct($uploads)
    {
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

        return (new MailMessage)
            ->subject('Document Upload Confirmation')
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line('We have successfully received ' . $fileCount . ' document(s) in the category: ' . $category . '.')
            ->line('Files uploaded:')
            ->lines($this->getFileList())
            ->line('Total files uploaded to date: ' . $notifiable->uploads()->count())
            ->action('View Your Documents', route('uploads.index'))
            ->line('If you have additional documents to upload, you can do so at any time through your client portal.')
            ->salutation('Best regards, Harbor Law');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
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
