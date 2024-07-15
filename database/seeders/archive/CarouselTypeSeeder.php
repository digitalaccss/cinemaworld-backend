<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\CarouselType;

class CarouselTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carouselTypes = [
            [
                'carousel_type' => 'Carousel'
            ],
            [
                'carousel_type' => 'Campaign'
            ],
        ];

        foreach ($carouselTypes as $carouselType) {
            CarouselType::create($carouselType);
        }
    }
}
