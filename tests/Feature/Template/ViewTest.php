<?php

use App\Models\EmailTemplate;

use function Pest\Laravel\get;

beforeEach(function() {
    login();
    $this->emailTemplate = EmailTemplate::factory()->create();
    $this->route = route('email-template.show', $this->emailTemplate);
});

it('should be able to open a template', function() {
    get($this->route)->assertViewHas('emailTemplate', $this->emailTemplate);
});

it('should make sure that the template is being displayed', function() {
    get($this->route)
        ->assertSee($this->emailTemplate->name)
        ->assertSee($this->emailTemplate->body);
});