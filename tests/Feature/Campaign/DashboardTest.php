<?php

use App\Models\Campaign;
use App\Models\CampaignEmail;
use App\Models\EmailList;
use App\Models\Subscriber;
use App\Models\EmailTemplate;

use function Pest\Laravel\get;

beforeEach(function() {
    login();
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

    $subscribers = $emailList->subscribers()->get();
    foreach($subscribers as $subscriber) {
        CampaignEmail::query()->create([
            'openings' => rand(1,20),
            'clicks' => rand(1,20),
            'campaign_id' => $this->campaign->id,
            'subscriber_id' => $subscriber->id,
            'sent_at' => $this->campaign->sent_at,
        ]);
    }
});

it('should show all statistics of the campaign', function() {
    get(route('campaign.show', ['campaign' => $this->campaign, 'what' => 'statistics']))
        ->assertViewHas('query', function($qry) {
            $expected = $this->campaign->mails()->statistics()->first();

            expect($qry)->total_openings->toBe($expected->total_openings);
            expect($qry)->total_subscribers->toBe($expected->total_subscribers);
            expect($qry)->unique_openings->toBe($expected->unique_openings);
            expect($qry)->total_clicks->toBe($expected->total_clicks);
            expect($qry)->unique_clicks->toBe($expected->unique_clicks);
            expect($qry)->open_rate->toBe($expected->open_rate);
            expect($qry)->click_rate->toBe($expected->click_rate);
            return true;
        });
});