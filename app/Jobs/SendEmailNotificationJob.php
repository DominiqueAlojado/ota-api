<?php

namespace App\Jobs;

use App\Mail\JobPostedMailNotification;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $dataset;
    public $moderatorEmail;

    public int $tries = 3;
    public int $backoff = 10;



    /**
     * Create a new job instance.
     */
    public function __construct($dataset, $moderatorEmail)
    {
        $this->dataset = $dataset;
        $this->moderatorEmail = $moderatorEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Simulate failure
        // Log::info("Attempting to send email to {$this->moderatorEmail}");
        // throw new Exception("Simulated failure to test retries.");
        Mail::to($this->moderatorEmail)->send(new JobPostedMailNotification($this->dataset));
    }
}
