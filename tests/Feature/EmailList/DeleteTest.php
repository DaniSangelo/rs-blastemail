<?php

namespace Tests\Feature\EmailList;

use App\Models\EmailList;
use App\Models\Subscriber;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    protected function setUp():void
    {
        parent::setUp();
        $this->login();
    }

    public function test_it_should_be_able_to_delete_an_email_list()
    {
        //arrange
        $emailList = EmailList::factory()->create();
        Subscriber::factory()->count(10)->create([
            'email_list_id' => $emailList->id,
        ]);

        //act
        $response = $this->delete(route('email-list.destroy', $emailList));

        // assert
        $response->assertRedirect(route('email-list.index'));
        $this->assertSoftDeleted('email_lists', ['id' => $emailList->id]);
        $this->assertSoftDeleted('subscribers', ['email_list_id' => $emailList->id]);
    }
}