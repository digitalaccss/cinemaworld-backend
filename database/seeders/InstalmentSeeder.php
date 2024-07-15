<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Instalment;

class InstalmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $instalments = [
            [
                'title' => 'The Dead Girls From Vienna',
                'foreign_title' => 'Die toten Mädchen von Wien',
                'slug' => 'the-dead-girls-from-vienna',
                'instalment_number' => 1,
                'release_year' => 2017,
                'runtime' => 93,
                'short_desc' => 'After a car bomb attack that killed his wife and left him blind, Inspector Haller remains determined to track down the murderer. With the help of...',
                'full_desc' => 'After a car bomb attack that killed his wife and left him blind, Inspector Haller remains determined to track down the murderer. With the help of a streetwise taxi driver, he tracks down the psychopath and sees that justice is served through to the end.',
                'trailer_link_url' => 'https://www.youtube.com/embed/gsxLxhX4QRY',
                'series_id' => 5
            ],
        ];

        foreach($instalments as $instalment){
            Instalment::create($instalment);
        }
    }
}
