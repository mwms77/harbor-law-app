<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\IntakeSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IntakeCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $submission;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, IntakeSubmission $submission)
    {
        $this->user = $user;
        $this->submission = $submission;
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
        $url = route('admin.users.show', $this->user);

        return (new MailMessage)
            ->subject('Client Completed Intake Form - ' . $this->user->full_name)
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line($this->user->full_name . ' (' . $this->user->email . ') has completed their estate planning intake form.')
            ->line('Completion time: ' . $this->submission->completed_at->format('F j, Y g:i A'))
            ->action('View Client Dashboard', $url)
            ->line('You can now review their intake information and begin preparing their estate plan.')
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
            'user_email' => $this->user->email,
            'completed_at' => $this->submission->completed_at,
        ];
    }
}
