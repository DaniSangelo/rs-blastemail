<?php

namespace App\Jobs;

use App\Mail\EmailCampaign;
use App\Models\Campaign;
use App\Models\CampaignEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailsCampaignJob implements ShouldQueue
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
                SendEmailCampaignJob::dispatch($this->campaign, $subscriber);
            }
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$subscriber->email}: " . $e->getMessage(), ['exception' => $e]);
        }
    }
}
