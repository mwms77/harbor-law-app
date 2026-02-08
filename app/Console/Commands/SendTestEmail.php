<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    protected $signature = 'test:email {to : Email address to send to}';

    protected $description = 'Send a test email (bypasses queue) to verify mail config';

    public function handle(): int
    {
        $to = $this->argument('to');

        $this->info('Sending test email to: ' . $to);
        $this->info('Mailer: ' . config('mail.default'));
        $this->info('From: ' . config('mail.from.address'));

        try {
            Mail::raw('This is a test email from Harbor Law Estate Planning. If you received this, mail is working.', function ($message) use ($to) {
                $message->to($to)
                    ->subject('Harbor Law – Test Email');
            });

            $this->info('Email sent successfully. Check inbox (and spam) for: ' . $to);
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed to send email:');
            $this->error($e->getMessage());
            $this->line('');
            $this->line('Full exception: ' . get_class($e));
            if ($e->getPrevious()) {
                $this->line('Previous: ' . $e->getPrevious()->getMessage());
            }
            return self::FAILURE;
        }
    }
}
