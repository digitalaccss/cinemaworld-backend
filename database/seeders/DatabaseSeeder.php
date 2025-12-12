<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // run multiple seeders in one seeder to populate data in one go
        $this->call([
            RegionSeeder::class,
            CountrySeeder::class,
            GenreSeeder::class,
            LanguageSeeder::class,
            ShowTypeSeeder::class,
            CarouselTypeSeeder::class,
            TagSeeder::class,
            DirectorSeeder::class,
            CastSeeder::class,
            ShowSeeder::class,
            AccoladeSeeder::class,
            CriticSeeder::class,
            InstalmentSeeder::class,
            PartnershipEventSeeder::class,
            PartnershipEventTypeSeeder::class,
            UserSeeder::class,
            CarouselSeeder::class,
            CampaignSeeder::class,
            SettingSeeder::class,
            ScheduleSeeder::class,
            BlogSeeder::class,
            BlogTypeSeeder::class,
        ]);
    }
}
