<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CampaignEmail;
use App\Models\Subscriber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Campaign::query()->with('emailList', 'emailList.subscribers')->get()
            ->each(function (Campaign $campaign) {
                $campaign->emailList->subscribers->each(function (Subscriber $subscriber) use ($campaign) {
                    CampaignEmail::factory()->create([
                        'campaign_id' => $campaign->id,
                        'subscriber_id' => $subscriber->id,
                        'sent_at' => $campaign->send_at,
                    ]);
                });
            });
    }
}
