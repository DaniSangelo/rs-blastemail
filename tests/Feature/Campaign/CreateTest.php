<?php

use App\Models\EmailList;
use App\Models\EmailTemplate;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function() {
    login();
    $this->route = route('campaign.create');
});

test('when saving we need to update campaigns::create session to have all the data', function() {
    EmailList::factory()->create();
    $template = EmailTemplate::factory()->create();

    post($this->route, [
        'name' => 'First Campaign',
        'subject' => 'Subject',
        'email_list_id' => 1,
        'email_template_id' => 1,
        'track_click' => true,
        'track_open' => true,
    ]);
    
    expect(session()->get('campaigns::create'))
        ->toBe([
            'name' => 'First Campaign',
            'subject' => 'Subject',
            'email_list_id' => 1,
            'email_template_id' => 1,
            'body' => $template->body,
            'track_click' => true,
            'track_open' => true,
            'send_at' => null,
            'send_when' => null,
        ]);
});

test('make sure that when we save the form we will be redirect back to the tamplate tab', function() {
    EmailList::factory()->create();
    EmailTemplate::factory()->create();
    
    $response = post($this->route, [
        'name' => 'First Campaign',
        'subject' => 'Subject',
        'email_list_id' => 1,
        'email_template_id' => 1,
        'track_click' => true,
        'track_open' => true,
    ]);
    $response->assertRedirect(route('campaign.create', ['tab' => 'template']));
});

it('it should have on the view a list of email lists', function() {
    EmailList::factory(5)->create();

    $response = get($this->route);
    $response->assertViewHas('emailLists', function($value) {
        expect($value)->toHaveCount(5);
        expect($value->first())->toBeInstanceOf(EmailList::class);
        return true;
    });
});

it('it should have on the view a list of templates', function() {
    EmailTemplate::factory(10)->create();
    $res = get($this->route);
    $res->assertViewHas('emailTemplates', function($value) {
        expect($value)->toHaveCount(10);
        expect($value->first())->toBeInstanceOf(EmailTemplate::class);
        return true;
    });
});

it('it should have on the view a blank tab variable', function() {
    $res = get($this->route);
    $res->assertViewHas('tab', null);
});

it('it should have on the view the form variable set to _config', function() {
    $res = get($this->route);
    $res->assertViewHas('form', '_config');
});

it('it should have on the view all the data in the session in the variable data', function() {
    EmailList::factory()->create();
    $template = EmailTemplate::factory()->create();

    post($this->route, [
        'name' => 'First Campaign',
        'subject' => 'Subject',
        'email_list_id' => 1,
        'email_template_id' => 1,
        'track_click' => true,
        'track_open' => true,
    ])->assertRedirect(route('campaign.create', ['tab' => 'template']));

    get($this->route, ['referer' => $this->route])
        ->assertViewHas('data', [
            'name' => 'First Campaign',
            'subject' => 'Subject',
            'email_list_id' => 1,
            'email_template_id' => 1,
            'body' => $template->body,
            'track_click' => true,
            'track_open' => true,
            'send_at' => null,
            'send_when' => null,
        ]);
});

test('if session is clear the variable data should have a default value', function() {
    expect(session()->get('campaign'))->toBeNull();

    get($this->route)
        ->assertViewHas('data', [
            'name' => null,
            'subject' => null,
            'email_list_id' => null,
            'email_template_id' => null,
            'body' => null,
            'track_click' => null,
            'track_open' => null,
            'send_at' => null,
            'send_when' => 'now'
    ]);
});

describe('validations', function () {
    test('required fields', function() {
        post($this->route)
            ->assertSessionHasErrors([
                'name' => __('validation.required', ['attribute' => 'name']),
                'subject' => __('validation.required', ['attribute' => 'subject']),
                'email_list_id' => __('validation.required', ['attribute' => 'email list id']),
                'email_template_id' => __('validation.required', ['attribute' => 'email template id']),
            ]);
    });

    test('name should have a max of 255 characteres', function() {
        post($this->route, ['name' => str_repeat('*', 256)])
            ->assertSessionHasErrors([
                'name' => __('validation.max.string', ['attribute' => 'name', 'max' => '255']),
            ]);
    });

    test('subject should have a max of 50 characteres', function() {
        post($this->route, ['subject' => str_repeat('*', 51)])
            ->assertSessionHasErrors([
                'subject' => __('validation.max.string', ['attribute' => 'subject', 'max' => '50']),
            ]);
    });

    test('valid email list', function() {
        post($this->route, ['email_list_id' => 12])
            ->assertSessionHasErrors([
                'email_list_id' => __('validation.exists', ['attribute' => 'email list id']),
            ]);
    });

    test('valid template', function() {
        post($this->route, ['email_template_id' => 12])
            ->assertSessionHasErrors([
                'email_template_id' => __('validation.exists', ['attribute' => 'email template id']),
            ]);
    });
});