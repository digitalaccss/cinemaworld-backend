<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\PartnershipEventType;

class PartnershipEventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $partnershipEventTypes = [
            [
                'partnership_event_type' => 'Partnership'
            ],
            [
                'partnership_event_type' => 'Events'
            ],
            // [
            //     'news_type' => 'Archive'
            // ],
        ];

        foreach($partnershipEventTypes as $partnershipEventType){
            PartnershipEventType::create($partnershipEventType);
        }
    }
}
