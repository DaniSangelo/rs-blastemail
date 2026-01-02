<?php

use App\Models\EmailList;
use App\Models\Subscriber;
use function Pest\Laravel\{assertDatabaseCount, assertSoftDeleted, delete};

beforeEach(function() {
    /** @var EmailList $emailList */
    $this->emailList = EmailList::factory()->create();
    login();
});

it('should be able to delete a subscriber', function() {
    $subscriber = Subscriber::create([
        'email_list_id' => $this->emailList->id,
        'name' => 'Daniel Sângelo',
        'email' => 'daniel.sangelo@example.com',
    ]);

    delete(route('subscribers.destroy', ['emailList' => $this->emailList, 'subscriber' => $subscriber->id]))
    ->assertRedirectBack();
    assertSoftDeleted('subscribers', ['id' => Subscriber::withTrashed()->first()->id]);
    assertDatabaseCount('subscribers', 1);
});