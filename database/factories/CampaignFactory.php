<?php

namespace Database\Factories;

use App\Models\EmailList;
use App\Models\EmailTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'subject' => fake()->words(3, true),
            'body' => fake()->sentence(25, true) . '<a href="https://google.com/">Clique aqui</a>',
            'track_click' => fake()->boolean(),
            'track_open' => fake()->boolean(),
            'send_at' => fake()->dateTime(),
            'email_list_id' => EmailList::factory(),
            'email_template_id' => EmailTemplate::factory(),
            'created_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'updated_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'deleted_at' => fake()->boolean()
                ? fake()->dateTimeBetween('-7 days', 'now')
                : null,
        ];
    }
}
