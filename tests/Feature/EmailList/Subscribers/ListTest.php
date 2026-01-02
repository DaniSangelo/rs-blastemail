<?php

use App\Models\EmailList;
use App\Models\Subscriber;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\{assertDatabaseCount, assertSoftDeleted, delete, get, getJson};

beforeEach(function() {
    /** @var EmailList $emailList */
    $this->emailList = EmailList::factory()->create();
    login();
});

it('only logged users can access the subscribers', function() {
    Auth::logout();
    getJson(route('subscribers.index', $this->emailList))
        ->assertUnauthorized();
});

it('should be possible see the entire list of subscribers', function() {
    Subscriber::factory()->count(10)->create([
        'email_list_id' => $this->emailList->id,
    ]);
    get(route('subscribers.index', $this->emailList))
        ->assertViewHas('subscribers', function($subscribers) {
            expect($subscribers)->toHaveCount(10);
            return true;
        });
});

it('should be able to search a subscriber', function() {
    Subscriber::factory()->count(10)->create([
        'email_list_id' => $this->emailList->id,
    ]);

    Subscriber::create([
        'email_list_id' => $this->emailList->id,
        'name' => 'Daniel Sângelo',
        'email' => 'daniel.sangelo@example.com',
    ]);

    $search = 'sangelo';
    $response = get(route('subscribers.index', ['emailList' => $this->emailList, 'search' => $search]));
    $response->assertViewHas('subscribers', function($subscribers) {
        expect($subscribers)->toHaveCount(1);
        return true;
    });
});

it('should ble able to show deleted records', function () {
    Subscriber::factory()->create(['deleted_at' => now()]);
    Subscriber::factory()->create();

    get(route('subscribers.index', ['emailList' => $this->emailList]))
        ->assertViewHas('subscribers', function ($value) {
            expect($value)
                ->count(1);

            return true;
        });

    get(route('subscribers.index', ['emailList' => $this->emailList, 'showTrashed' => 1]))
        ->assertViewHas('subscribers', function ($value) {
            expect($value)
                ->count(2);

            return true;
        });
});

it('should be paginated', function() {
    Subscriber::factory()->count(30)->create();
    Subscriber::factory()->create();

    get(route('subscribers.index', ['emailList' => $this->emailList]))
        ->assertViewHas('subscribers', function ($value) {

            expect($value)->count(15);
            expect($value)->toBeInstanceOf(LengthAwarePaginator::class);

            return true;
        });
});