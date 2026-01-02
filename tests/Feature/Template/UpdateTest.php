<?php

use App\Models\EmailTemplate;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\put;

beforeEach(function () {
    login();

    $this->template = EmailTemplate::factory()->create([
        'name' => 'Template Master',
        'body' => '<span>Hello Word!</span>'
    ]);

    $this->route = route('email-template.update', $this->template);
});

it('should be able to update a template', function () {

    put($this->route, ['name' => 'Changing template', 'body' => '<span>Has Changed</span>'])
        ->assertRedirect()
        ->assertSessionHas('message', __('Template updated successfully'));

    assertDatabaseHas('email_templates', [
        'id' => $this->template->id,
        'name' => 'Changing template',
        'body' => '<span>Has Changed</span>',
    ]);
});

it('name should be required', function () {
    put($this->route, ['name' => null, 'body' => '<span>Hello Word!</span>'])
        ->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => 'name'])]);
});

it('name should have a max of 255 character', function () {
    put($this->route, ['name' => str_repeat('*', 256), 'body' => '<span>Hello Word!</span>'])
        ->assertSessionHasErrors(['name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255])]);
});

it('body should be required', function () {
    put($this->route, ['name' => null, 'body' => null])
        ->assertSessionHasErrors(['body' => __('validation.required', ['attribute' => 'body'])]);
});