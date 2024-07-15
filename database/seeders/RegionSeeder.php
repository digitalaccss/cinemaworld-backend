<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = [
            [
                'region_display_name' => 'All Regions',
                'region_name' => 'AllRegions'
            ],
            [
                'region_display_name' => 'Africa',
                'region_name' => 'Africa'
            ],
            [
                'region_display_name' => 'Asia',
                'region_name' => 'Asia'
            ],
            [
                'region_display_name' => 'Australia & New Zealand',
                'region_name' => 'Australia&NewZealand'
            ],
            [
                'region_display_name' => 'Central Asia',
                'region_name' => 'CentralAsia'
            ],
            [
                'region_display_name' => 'Europe',
                'region_name' => 'Europe'
            ],
            [
                'region_display_name' => 'Latin America',
                'region_name' => 'LatinAmerica'
            ],
            [
                'region_display_name' => 'Middle East',
                'region_name' => 'MiddleEast'
            ],
            [
                'region_display_name' => 'US & Canada',
                'region_name' => 'US&Canada'
            ]
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}
