<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            // [
            //     'tag_display_name' => 'New',
            // ],
            // [
            //     'tag_display_name' => 'Films',
            //     // 'tag_name' => 'Films'
            // ],
            // [
            //     'tag_display_name' => 'Series',
            //     // 'tag_name' => 'Series'
            // ],
            // [
            //     'tag_display_name' => 'Documentaries',
            //     // 'tag_name' => 'Documentaries'
            // ],
            // [
            //     'tag_display_name' => 'Shorts',
            //     // 'tag_name' => 'Shorts'
            // ],
            [
                'tag_display_name' => 'Busan Festival',
                // 'tag_name' => 'BusanFestival'
            ],
            [
                'tag_display_name' => 'Sundance Film Festival',
                // 'tag_name' => 'SundanceFilmFestival'
            ]
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
