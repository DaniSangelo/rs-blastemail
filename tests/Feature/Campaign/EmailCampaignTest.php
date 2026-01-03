<?php

use App\Mail\EmailCampaign;
use App\Models\Campaign;
use App\Models\CampaignEmail;
use App\Models\EmailList;
use App\Models\Subscriber;
use App\Models\EmailTemplate;

test('links on the body should be replace with the tracking link', function() {
    $template = EmailTemplate::factory()->create([
        'body' => '<div>Hello Word! <a href="https://www.google.com">Click here</a></div>'
    ]);

    $emailList = EmailList::factory()->has(Subscriber::factory()->count(3))->create();
    $campaign = Campaign::factory()->for($emailList)->create(['body' => $template->body, 'send_at' => now()->addDays(2)->format('Y-m-d')]);
    $subscriber = $emailList->subscribers->first();

    $mail = CampaignEmail::query()->create(['campaign_id' => $campaign->id, 'subscriber_id' => $subscriber->id,'sent_at' => $campaign->sent_at]);

    $email = (new EmailCampaign($campaign, $mail))->render();

    $pattern = '/href="([^"]*)google.com"/';
    preg_match_all($pattern, $email, $matches);
    
    $value = $matches[1][0];
    expect($value)->toBe(
        route('tracking.clicks', ['mail' => $mail, 'forward' => 'https://www.'])
    );
});