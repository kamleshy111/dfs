<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Mail\AlertDeviceDetail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;



class alertMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $alert;
    protected $customerEmail;

    /**
     * Create a new job instance.
     */
    public function __construct($alert, $customerEmail)
    {
        $this->alert = $alert;
        $this->customerEmail = $customerEmail;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Log the email sending attempt
        Log::info('Attempting to send email to: ' . $this->customerEmail);

        try {
            // Send the email
            Mail::to($this->customerEmail)->send(new AlertDeviceDetail($this->alert));

            // Log successful email sending
            Log::info('Email sent successfully to: ' . $this->customerEmail);
        } catch (\Exception $e) {
            // Log any errors during the sending process
            Log::error('Failed to send email to: ' . $this->customerEmail . '. Error: ' . $e->getMessage());
        }

    }

}
