<?php

use App\Models\EmailList;
use App\Models\Subscriber;
use function Pest\Laravel\{assertDatabaseCount, assertSoftDeleted, delete};

beforeEach(function() {
    login();
});

it('should be able to delete a subscriber', function() {
    $subscriber = Subscriber::factory()->create();
    delete(
        route(
            'subscribers.destroy', [
                'emailList' => $subscriber->email_list_id,
                'subscriber' => $subscriber->id
            ]
        )
    )->assertRedirectBack();
    assertSoftDeleted('subscribers', ['id' => Subscriber::withTrashed()->first()->id]);
    assertDatabaseCount('subscribers', 1);
});