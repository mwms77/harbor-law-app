<?php

namespace App\Notifications;

use App\Models\EstatePlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstatePlanReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $estatePlan;

    /**
     * Create a new notification instance.
     */
    public function __construct(EstatePlan $estatePlan)
    {
        $this->estatePlan = $estatePlan;
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
        $url = route('estate-plans.view', $this->estatePlan);
        $downloadUrl = route('estate-plans.download', $this->estatePlan);

        return (new MailMessage)
            ->subject('Your Estate Plan is Ready')
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line('Great news! Your estate planning documents have been completed and are ready for your review.')
            ->line('Document: ' . $this->estatePlan->original_filename)
            ->line('Status: ' . ucfirst($this->estatePlan->status))
            ->action('View Your Estate Plan', $url)
            ->line('You can also download your documents directly from your client portal.')
            ->line('**Next Steps:**')
            ->line('1. Review your estate planning documents carefully')
            ->line('2. Contact us if you have any questions or need revisions')
            ->line('3. Schedule an appointment to execute your documents if required')
            ->line('If you have any questions about your estate plan, please don\'t hesitate to reach out.')
            ->salutation('Best regards, Harbor Law');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'estate_plan_id' => $this->estatePlan->id,
            'filename' => $this->estatePlan->original_filename,
            'status' => $this->estatePlan->status,
        ];
    }
}
