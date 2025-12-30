<?php

use Illuminate\Http\UploadedFile;
use function Pest\Laravel\post;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    login();
});

test('it should be able to create an email list', function () {
    // $this->withoutExceptionHandling();
    $data = [
        'title' => 'Email list test',
        'listFile' => UploadedFile::fake()->createWithContent(
            'sample_names.csv',
            <<<CSV
                name;email
                Joe Doe;joe.doe@example.com
                CSV
        ),
    ];

    $request = post(route('email-list.create'), $data);

    $request->assertRedirectToRoute('email-list.index');
    assertDatabaseHas('email_lists', [
        'title' => 'Email list test',
    ]);

    assertDatabaseHas('subscribers', [
        'email_list_id' => 1,
        'name' => 'Joe Doe',
        'email' => 'joe.doe@example.com',
    ]);
});

test('title should be required', function () {
    post(route('email-list.create'), [])
        ->assertSessionHasErrors(['title' => 'The title field is required.']);
});

test('title should have a max of 255 characters', function () {
    post(route('email-list.create'), ['title' => str_repeat('*', 256)])
        ->assertSessionHasErrors(['title' => 'The title field must not be greater than 255 characters.']);
});

test('file should be required', function () {
    post(route('email-list.create'), [])
        ->assertSessionHasErrors(['listFile' => 'The list file field is required.']);
});
