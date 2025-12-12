<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\PartnershipEvent;

class PartnershipEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $partnershipEvents = [
            [
                'title' => 'vOilah! France Singapore Festival',
                'slogan' => "Celebrate the spirits of Joy and Hope with the 'Feel Good' edition of the France Singapore Festival",
                'slug' => 'voilah-france-singapore-festival',
                'partnership_event_type_id' => 1,
                'facebook_link_url' => 'https://www.facebook.com/sharer/sharer.php?u=https://cinemaworld.asia/voilah-france-singapore-festival/'
            ],
            [
                'title' => 'German Film Festival 2020',
                'slogan' => "One of Singapore's longest running foreign film festival returns once again!",
                'slug' => 'german-film-festival-2020',
                'partnership_event_type_id' => 2,
                'facebook_link_url' => 'https://www.facebook.com/sharer/sharer.php?u=https://cinemaworld.asia/german-film-festival-2020/'
            ],
            [
                'title' => "CinemaWorld Brings New Danish Crime Series DNA, from the Co-Creator of 'The Killing', to Asia",
                // 'slogan' => '',
                'slug' => 'cinemaworld-brings-new-danish-crime-series-dna-from-the-co-creator-of-the-killing-to-asia',
                'partnership_event_type_id' => 2,
                'facebook_link_url' => 'https://www.facebook.com/sharer/sharer.php?u=https://cinemaworld.asia/cinemaworld-brings-new-danish-crime-series-dna-from-the-co-creator-of-the-killing-to-asia/'
            ],
        ];

        foreach($partnershipEvents as $partnershipEvent){
            PartnershipEvent::create($partnershipEvent);
        }
    }
}
