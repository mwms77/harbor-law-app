<?php

namespace App\Mail;

use App\Models\EstatePlan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EstatePlanUploaded extends Mailable
{
    use Queueable, SerializesModels;

    public $estatePlan;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(EstatePlan $estatePlan, User $user)
    {
        $this->estatePlan = $estatePlan;
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $statusLabels = [
            'draft' => 'Draft',
            'final' => 'Final',
            'executed' => 'Executed',
        ];
        
        $statusLabel = $statusLabels[$this->estatePlan->status] ?? 'New';

        return $this->subject('New Estate Plan Document Available')
            ->view('emails.estate-plan-uploaded')
            ->with([
                'userName' => $this->user->first_name,
                'documentName' => $this->estatePlan->original_filename,
                'status' => $statusLabel,
                'uploadDate' => $this->estatePlan->created_at->format('F j, Y'),
                'dashboardUrl' => route('dashboard'),
            ]);
    }
}
