<?php

namespace Tests\Feature\EmailList;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CreateTest extends TestCase
{
    protected function setUp():void
    {
        parent::setUp();
        $this->login();
    }

    public function test_it_should_be_able_to_create_an_email_list()
    {
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

        $request = $this->post(route('email-list.create'), $data);

        $request->assertRedirectToRoute('email-list.index');
        $this->assertDatabaseHas('email_lists', [
            'title' => 'Email list test',
        ]);

        $this->assertDatabaseHas('subscribers', [
            'email_list_id' => 1,
            'name' => 'Joe Doe',
            'email' => 'joe.doe@example.com',
        ]);
    }

    public function test_title_should_be_required()
    {
        $this->post(route('email-list.create'), [])
            ->assertSessionHasErrors(['title' => 'The title field is required.']);
    }

     public function test_title_should_have_a_max_of_255_characters()
    {
        $this->post(route('email-list.create'), ['title' => str_repeat('*', 256)])
            ->assertSessionHasErrors(['title' => 'The title field must not be greater than 255 characters.']);
    }

    public function test_file_should_be_required()
    {
        $this->post(route('email-list.create'), [])
            ->assertSessionHasErrors(['listFile' => 'The list file field is required.']);
    }
}