<?php

use App\Models\Campaign;
use App\Models\CampaignEmail;
use App\Models\EmailList;
use App\Models\Subscriber;
use App\Models\EmailTemplate;

use function Pest\Laravel\get;

beforeEach(function() {
    $template = EmailTemplate::factory()->create([
        'body' => '<div>Hello Word! <a href="https://www.google.com">Click here</a></div>'
    ]);
    $emailList = EmailList::factory()->has(Subscriber::factory()->count(3))->create();
    $this->campaign = Campaign::factory()->for($emailList)->create([
        'body' => $template->body,
        'send_at' => now()->addDays(2)->format('Y-m-d'),
        'track_click' => true,
        'deleted_at' => null,
    ]);
    $subscriber = $emailList->subscribers->first();

    $this->mail = CampaignEmail::query()->create([
        'clicks' => 0,
        'campaign_id' => $this->campaign->id,
        'subscriber_id' => $subscriber->id,
        'sent_at' => $this->campaign->sent_at,
    ]);
});

it('should increment clicks on the database if the campaign is tracking clicks', function() {
    get(route('tracking.clicks', ['mail' => $this->mail, 'forward' => 'https://www.google.com']));
    expect($this->mail)->refresh()->clicks->toBe(1);
});

it('should not increment clicks on the database if the campaign is not tracking clicks', function() {
    $this->campaign->update(['track_click' => false]);
    get(route('tracking.clicks', ['mail' => $this->mail, 'forward' => 'https://www.google.com']));
    expect($this->mail)->refresh()->clicks->toBe(0);
});

it('should redirect the user to the given url', function() {
    get(route('tracking.clicks', ['mail' => $this->mail, 'forward' => 'https://www.google.com']))
        ->assertRedirect('https://www.google.com');
});