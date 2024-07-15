<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            [
                'country_display_name' => 'All Countries',
                'country_name' => 'AllCountries'
            ],
            [
                'country_display_name' => 'Brunei',
                'country_name' => 'Brunei'
            ],
            [
                'country_display_name' => 'Indonesia',
                'country_name' => 'Indonesia'
            ],
            [
                'country_display_name' => 'Malaysia',
                'country_name' => 'Malaysia'
            ],
            [
                'country_display_name' => 'Philipines',
                'country_name' => 'Philipines'
            ],
            [
                'country_display_name' => 'Singapore',
                'country_name' => 'Singapore'
            ],
            [
                'country_display_name' => 'Sri Lanka',
                'country_name' => 'SriLanka'
            ]
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
