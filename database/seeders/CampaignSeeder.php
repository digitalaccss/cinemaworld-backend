<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Campaign;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campaigns = [
            [
                'title' => 'Love In Transit',
                'slogan' => '"There is nothing more truly artistic than to love people." - Vincent Van Gogh',
                'slug' => 'love-in-transit',
                'carousel_id' => 5,
                'facebook_link_url' => 'https://www.facebook.com/sharer/sharer.php?u=https://cinemaworld.asia/love-in-transit/'
            ],
        ];

        foreach($campaigns as $campaign){
            Campaign::create($campaign);
        }
    }
}
