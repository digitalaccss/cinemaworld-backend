<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = [
            'site_title' => 'Cinemaworld',
            'site_address_url' => 'https://cinemaworld.asia/',
            'meta_title' => 'Home - Cinemaworld',
            'meta_description' => 'Subscribe to CinemaWorld? See schedule. Not yet? Subscribe now. Menu ... Follow Us. © 2022 CinemaWorld. All rights reserved.'
        ];

        Setting::create($setting);
    }
}
