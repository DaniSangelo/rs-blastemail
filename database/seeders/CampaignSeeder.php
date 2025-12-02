<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\EmailList;
use App\Models\EmailTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaigns = Campaign::factory()->count(10)->create();

        for($i=0; $i < count($campaigns); $i++) {
            $emailList = EmailList::query()->inRandomOrder()->first();
            $template = EmailTemplate::query()->inRandomOrder()->first();
            Campaign::factory()->create([
                'email_list_id' => $emailList->id,
                'email_template_id'=> $template->id,
            ]);
        }
    }
}
