<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Carousel;

class CarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carousels = [
            [
                'carousel_display_name' => 'Trending Now',
                // 'carousel_type_id' => 1,
                'shows' => ["2", "3", "5", "4", "1"]
            ],
            [
                'carousel_display_name' => 'Featured Films',
                // 'carousel_type_id' => 1,
                'shows' => ["3", "2", "5", "4"]
            ],
            [
                'carousel_display_name' => 'Featured Series',
                // 'carousel_type_id' => 1,
                'shows' => ["3", "4", "1", "2"]
            ],
            [
                'carousel_display_name' => 'Featured Shorts',
                // 'carousel_type_id' => 1,
                'shows' => ["2", "4", "1"]
            ],
            [
                'carousel_display_name' => "Valentine's Day",
                // 'carousel_type_id' => 2,
                'shows' => ["3", "2"]
            ]
        ];

        foreach($carousels as $carousel){
            Carousel::create($carousel);
        }
    }
}
