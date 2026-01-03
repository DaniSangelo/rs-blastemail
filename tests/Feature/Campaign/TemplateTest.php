<?php

use App\Models\EmailList;
use App\Models\EmailTemplate;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function() {
    login();

    $this->template = EmailTemplate::factory()->create();
    $this->route = route('campaign.create', ['tab' => 'template']);

    post(route('campaign.create'), [
        'name' => 'First Campaign',
        'subject' => 'Subject',
        'email_list_id' => 1,
        'email_template_id' => 1,
        'track_click' => true,
        'track_open' => true,
    ]);
});

test('when submiting the form with a body it should be redirect to the schedule tab', function() {
    $res = post($this->route, [
        'body' => 'Body',
    ]);

    $res->assertRedirect(route('campaign.create', ['tab' => 'schedule']));
});

test('when submiting the form with a body the session should be updated with the body information', function() {
    post($this->route, ['body' => 'fake body'])->assertSessionHasNoErrors();
    expect(session()->get('campaigns::create')['body'])->toBe('fake body');
});

test('if the data is not filled we need to redirect back to setup', function() {
    session()->forget('campaigns::create');

    get($this->route)->assertRedirect(route('campaign.create'));
});

test('view should have tab variable as template', function() {
    get($this->route, ['referer' => $this->route])->assertViewHas('tab', 'template');
});

test('view should have form variable as _template', function() {
    get($this->route, ['referer' => $this->route])->assertViewHas('form', '_template');
});

test('data should have been filled with the body of the given template', function() {
    get($this->route, ['referer' => $this->route])->assertViewHas('data.body', $this->template->body);

    /* OR...
    $res = get($this->route, ['referer' => $this->route]);
    $res->assertViewHas('data', function ($data) {
        return $data['body'] === $this->template->body;
    });
    */
});

test('body should be required', function() {
    post($this->route)->assertSessionHasErrors([
        'body' => __('validation.required', ['attribute' => 'body']),
    ]);
});