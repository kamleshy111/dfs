<?php

namespace App\Jobs;

use App\Mail\SendLoginDetail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ClientRegistration implements ShouldQueue
{
    use Queueable;

    protected $data;
    protected $customerEmail;

    /**
     * Create a new job instance.
     */
    public function __construct($loginData, $customerEmail)
    {
        $this->data = $loginData;
        $this->customerEmail = $customerEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->customerEmail)->send(new SendLoginDetail($this->data));

            // Log successful email sending
            Log::info('Registration Email sent successfully to: ' . $this->customerEmail);
        } catch (\Exception $e) {
            Log::error('Registration email Failed to send : ' . $this->customerEmail . '. Error: ' . $e->getMessage());
        }
    }
}
