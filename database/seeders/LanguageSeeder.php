<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            [
                'language_display_name' => 'All Languages',
                'language_name' => 'AllLanguages'
            ],
            [
                'language_display_name' => 'Chinese',
                'language_name' => 'Chinese'
            ],

            [
                'language_display_name' => 'Cantonese',
                'language_name' => 'Cantonese'
            ],
            [
                'language_display_name' => 'English',
                'language_name' => 'English'
            ],
            [
                'language_display_name' => 'Filipino',
                'language_name' => 'Filipino'
            ],
            [
                'language_display_name' => 'Indonesian',
                'language_name' => 'Indonesian'
            ],
            [
                'language_display_name' => 'Malay',
                'language_name' => 'Malay'
            ],
            [
                'language_display_name' => 'Sinhalese',
                'language_name' => 'Sinhalese'
            ]
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
