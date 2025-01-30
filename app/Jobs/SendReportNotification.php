<?php

namespace App\Jobs;

use Exception;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReportNotification implements ShouldQueue
{
    use Queueable;

    protected $inform;
    public function __construct($inform)
    {
        $this->inform = $inform;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            //// I will replace this email with admin email
            Mail::to('hani770894642@gmail.com')->send(new \App\Mail\ReportNotificationMail($this->inform));
        }
        catch(Exception $e){
            Log::error('Error in Job: ' . $e->getMessage());
        }

    }
}
