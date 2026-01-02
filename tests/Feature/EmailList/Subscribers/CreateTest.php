<?php

use App\Models\EmailList;
use App\Models\Subscriber;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

beforeEach(function () {
    login();

    $this->emailList = EmailList::factory()->create();
    $this->createRoute = route('subscribers.create', $this->emailList);
});

it('should be able to create a new subscriber', function () {
    post($this->createRoute, [
        'name' => 'Joe Doe',
        'email' => 'joe@doe.com',
    ])->assertRedirect(route('subscribers.index', $this->emailList));

    assertDatabaseHas('subscribers', [
        'email_list_id' => $this->emailList->id,
        'name' => 'Joe Doe',
        'email' => 'joe@doe.com',
    ]);
});

it('name should be required', function () {
    post($this->createRoute, [
        'name' => '',
        'email' => 'joe@doe.com',
    ])->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => 'name'])]);
});

it('name should have a max of 255 character', function () {
    post($this->createRoute, [
        'name' => str_repeat('*', 256),
        'email' => 'joe@doe.com',
    ])->assertSessionHasErrors(['name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255])]);
});

it('email should be required', function () {
    post($this->createRoute, [
        'name' => 'Joe Doe',
        'email' => '',
    ])->assertSessionHasErrors(['email' => __('validation.required', ['attribute' => 'email'])]);    
});

it('email should have a max of 255 character', function () {
    post($this->createRoute, [
        'name' => 'Jon',
        'email' => str_repeat('%', 256),
    ])->assertSessionHasErrors(['email' => __('validation.max.string', ['attribute' => 'email', 'max' => 255])]);
});

it('email should be unique inside an email list', function () {
    Subscriber::factory()->create([
        'email_list_id' => $this->emailList->id,
        'name' => 'Joe Doe',
        'email' => 'joe@doe.com',
    ]);
    post($this->createRoute, [
        'name' => 'Joe Doe',
        'email' => 'joe@doe.com',
    ])->assertSessionHasErrors(['email' => __('validation.unique', ['attribute' => 'email', 'values' => 'joe@doe.com'])]);
});

it ('should be a valid email', function() {
    post($this->createRoute, [
        'name' => 'Jon',
        'email' => 'jon',
    ])->assertSessionHasErrors(['email' => __('validation.email', ['attribute' => 'email'])]);
});