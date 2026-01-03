<?php

use App\Jobs\SendEmailsCampaignJob;
use App\Models\EmailList;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Bus;

use function Pest\Laravel\{assertDatabaseCount, get, post};

beforeEach(function() {
    login();

    EmailList::factory()->create();
    $this->template = EmailTemplate::factory()->create();
    $this->route = route('campaign.create', ['tab' => 'schedule']);

    post(route('campaign.create'), [
        'name' => 'First Campaign',
        'subject' => 'Subject',
        'email_list_id' => 1,
        'email_template_id' => 1,
        'track_click' => true,
        'track_open' => true,
        'body' => $this->template->body,
    ]);

    Bus::fake();
});

test('all the data should be filled before entering the tab schedule', function() {
    get($this->route, ['referer' => $this->route])
        ->assertOk();

    session()->forget('campaigns::create');

    get($this->route, ['referer' => $this->route])
        ->assertRedirect(route('campaign.create'));
});

test('when sending now it should just create the campaign', function() {
    post($this->route, ['send_when' => 'now'])
        ->assertSessionHasNoErrors();

    assertDatabaseCount('campaigns', 1);
});

test('when sending later the send at should be required', function() {
    post($this->route, ['send_when' => 'later'])
        ->assertSessionHasErrors([
            'send_at' => __('validation.required', ['attribute' => 'send at']),
        ]);
});

test('campaign should be created when sending later', function() {
    post($this->route, ['send_when' => 'later', 'send_at' => '2026-01-04'])
        ->assertSessionHasNoErrors();

    assertDatabaseCount('campaigns', 1);
});

test('send_at should be in the future when send_when is later', function() {
    post($this->route, ['send_when' => 'later', 'send_at' => '2026-01-04'])
        ->assertSessionHasNoErrors([
            'send_at' => __('validation.after', ['attribute' => 'send at']),
        ]);
});

test('send_at should be in the future when send_when is later2', function() {
    post($this->route, ['send_when' => 'later', 'send_at' => '2025-01-04'])
        ->assertSessionHasErrors([
            'send_at' => __('validation.after', ['attribute' => 'send at', 'date' => 'today']),
        ]);
});

test('checking if the job is being queued', function() {
    post($this->route, ['send_when' => 'now']);
    Bus::assertDispatchedAfterResponse(SendEmailsCampaignJob::class);
});

test('after everything we should redirect to campaign index route', function() {
    post($this->route, ['send_when' => 'now'])->assertRedirect(route('campaign.index'));
});