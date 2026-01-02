<?php

use App\Models\EmailTemplate;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

beforeEach(function () {
    login();
});

it('only logged users can access templates', function () {
    Auth::logout();

    getJson(route('email-template.index'))->assertUnauthorized();
});

it('should ble possible see the entire list of templates', function () {
    EmailTemplate::factory()->count(5)->create();

    get(route('email-template.index'))
        ->assertViewHas('templates', function ($value) {
            expect($value)->count(5);

            return true;
        });
});

it('should be able to search a template by name', function () {
    EmailTemplate::factory()->count(5)->create();
    EmailTemplate::factory()->create(['name' => 'Charlie Smith']);

    get(route('email-template.index', ['search' => 'Charlie']))
        ->assertViewHas('templates', function ($value) {
            expect($value)->count(1);

            expect($value)->first()->id->toBe(6);

            return true;
        });
});

it('should be able to search by id', function () {
    EmailTemplate::factory()->create(['name' => 'Joe Doe']);
    EmailTemplate::factory()->create(['name' => 'Jane Doe']);

    get(route('email-template.index', ['search' => 2]))
        ->assertViewHas('templates', function ($value) {
            expect($value)
                ->count(1);

            expect($value)->first()->id->toBe(2);

            return true;
        });
});

it('should be able to show deleted records', function () {
    EmailTemplate::factory()->create(['deleted_at' => now()]);
    EmailTemplate::factory()->create();

    get(route('email-template.index'))
        ->assertViewHas('templates', function ($value) {
            expect($value)->count(1);

            return true;
        });

    get(route('email-template.index', ['showTrashed' => 1]))
        ->assertViewHas('templates', function ($value) {
            expect($value)->count(2);

            return true;
        });
});

it('should ble paginated', function () {
    EmailTemplate::factory()->count(30)->create();
    EmailTemplate::factory()->create();

    get(route('email-template.index'))
        ->assertViewHas('templates', function ($value) {

            expect($value)->count(15);
            expect($value)->toBeInstanceOf(LengthAwarePaginator::class);

            return true;
        });
});