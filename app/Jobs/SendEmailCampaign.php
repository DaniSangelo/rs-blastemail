<?php

namespace App\Jobs;

use App\Mail\EmailCampaign;
use App\Models\Campaign;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailCampaign implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Campaign $campaign)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            foreach($this->campaign->emailList->subscribers as $subscriber) {
                Mail::to($subscriber->email)
                    ->later(
                        $this->campaign->send_at,
                        new EmailCampaign($this->campaign)
                    );
            }
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$subscriber->email}: " . $e->getMessage(), ['exception' => $e]);
        }
    }
}
