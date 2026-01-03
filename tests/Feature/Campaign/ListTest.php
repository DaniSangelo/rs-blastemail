<?php

use App\Models\Campaign;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

beforeEach(function () {
    login();
});

it('only logged users can access campaigns', function () {
    Auth::logout();

    getJson(route('campaign.index'))
        ->assertUnauthorized();
});

it('should ble possible see the entire list of campaigns', function () {
    Campaign::factory()->count(5)->create();

    get(route('campaign.index'))
        ->assertViewHas('campaigns', function ($value) {
            expect($value)->count(5);
            return true;
        });
});

it('should be able to search a campaign by name', function () {
    Campaign::factory()->count(5)->create();
    Campaign::factory()->create(['name' => 'Charlie Smith', 'deleted_at' => null]);

    get(route('campaign.index', ['search' => 'Charlie']))
        ->assertViewHas('campaigns', function ($value) {
            expect($value)->count(1);
            expect($value)->first()->id->toBe(6);
            return true;
        });
});

it('should be able to show deleted records', function () {
    Campaign::factory()->create(['deleted_at' => now()]);
    Campaign::factory()->create();

    get(route('campaign.index'))
        ->assertViewHas('campaigns', function ($value) {
            expect($value)->count(1);
            return true;
        });

    get(route('campaign.index', ['showTrashed' => 1]))
        ->assertViewHas('campaigns', function ($value) {
            expect($value)->count(2);
            return true;
        });
});

it('should le paginated', function () {
    Campaign::factory()->count(30)->create();
    Campaign::factory()->create();

    get(route('campaign.index'))
        ->assertViewHas('campaigns', function ($value) {
            expect($value)->toBeInstanceOf(LengthAwarePaginator::class);
            expect($value->items())->toHaveCount(5);
            expect($value->total())->toBe(
                Campaign::query()->count()
            );

            return true;
        }); 
});

it('should return correct number of items on the last page', function () {
    Campaign::factory()->count(16)->create(['deleted_at' => null]);

    get(route('campaign.index', ['page' => 4]))
        ->assertViewHas('campaigns', function (LengthAwarePaginator $paginator) {
            $expected = $paginator->total() % $paginator->perPage();

            $expected = $expected === 0
                ? $paginator->perPage()
                : $expected;

            expect($paginator->items())->toHaveCount($expected);

            return true;
        });
});