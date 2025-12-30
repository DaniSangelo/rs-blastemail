<?php

use App\Models\EmailList;
use App\Models\Subscriber;
use function Pest\Laravel\delete;
use function Pest\Laravel\assertSoftDeleted;

beforeEach(function () {
    login();
});

test('it should be able to delete an email list', function () {
    //arrange
    $emailList = EmailList::factory()->create();
    Subscriber::factory()->count(10)->create([
        'email_list_id' => $emailList->id,
    ]);

    //act
    $response = delete(route('email-list.destroy', $emailList));

    // assert
    $response->assertRedirect(route('email-list.index'));
    assertSoftDeleted('email_lists', ['id' => $emailList->id]);
    assertSoftDeleted('subscribers', ['email_list_id' => $emailList->id]);
});
