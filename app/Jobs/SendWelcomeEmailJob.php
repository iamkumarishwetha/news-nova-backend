<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\WelcomeMail;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Queueable;
    public $user;
    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
                Mail::to($this->user->email)
                ->send(new WelcomeMail($this->user));
            } catch (\Exception $e) {
                Log::error($e->getMessage());
        }
    }
}
