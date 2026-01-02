<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

beforeEach(function () {
    login();
    $this->route = route('email-template.store');
});

it('should be able to create a new template', function () {
    post($this->route, ['name' => 'Joe Doe', 'body' => '<span>Hello Word!</span>'])
        ->assertRedirect(route('email-template.index'));

    assertDatabaseHas('email_templates', [
        'name' => 'Joe Doe',
        'body' => '<span>Hello Word!</span>',
    ]);
});

it('name should be required', function () {
    post($this->route, ['name' => null, 'body' => '<span>Hello Word!</span>'])
        ->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => 'name'])]);
});

it('name should have a max of 255 character', function () {
    post($this->route, ['name' => str_repeat('*', 256), 'body' => '<span>Hello Word!</span>'])
        ->assertSessionHasErrors(['name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255])]);
});

it('body should be required', function () {
    post($this->route, ['name' => null, 'body' => null])
        ->assertSessionHasErrors(['body' => __('validation.required', ['attribute' => 'body'])]);
});