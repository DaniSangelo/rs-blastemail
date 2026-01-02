<?php

use App\Models\EmailList;
use Illuminate\Pagination\LengthAwarePaginator;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

it('it should be paginated', function () {
    login();
    EmailList::factory()->count(40)->create();

    $response = get(route('email-list.index'));
    $response->assertViewHas('emailLists', function($list) {
        expect($list)->toBeInstanceOf(LengthAwarePaginator::class);
        expect($list)->toHaveCount(5);
        return true;
    });
});

it('it should be able to search a list', function () {
    login();

    EmailList::factory()->count(10)->create();
    EmailList::factory()->create(['title' => 'Title 1']);
    $emailList = EmailList::factory()->create(['title' => 'Title Testing 2']);

    $response = get(route('email-list.index', ['search' => 'Testing 2']));

    $response->assertViewHas('emailLists', function($list) use ($emailList) {
        expect($list)->toHaveCount(1);
        expect($list->first()->id)->toEqual($emailList->id);
        return true;
    });
});

it('needs to be authenticated', function () {
    getJson(route('email-list.index'))->assertUnauthorized();

    login();
    get(route('email-list.index'))->assertSuccessful();
});
