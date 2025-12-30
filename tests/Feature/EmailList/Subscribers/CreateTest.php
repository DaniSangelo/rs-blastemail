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
    ])->assertSessionHasErrors(['name' => 'The name field is required.']);
});

it('name should have a max of 255 character', function () {
    post($this->createRoute, [
        'name' => str_repeat('*', 256),
        'email' => 'joe@doe.com',
    ])->assertSessionHasErrors(['name' => 'The name field must not be greater than 255 characters.']);
});

it('email should be required', function () {
    post($this->createRoute, [
        'name' => 'Joe Doe',
        'email' => '',
    ])->assertSessionHasErrors(['email' => 'The email field is required.']);    
});

it('email should have a max of 255 character', function () {
    post($this->createRoute, [
        'name' => 'Jon',
        'email' => str_repeat('%', 256),
    ])->assertSessionHasErrors(['email' => 'The email field must not be greater than 255 characters.']);
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
    ])->assertSessionHasErrors(['email' => 'The email has already been taken.']);
});