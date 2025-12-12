<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Cast;

class CastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // insert 3 rows of data into database with specified cols from cast factory
        // $casts = Cast::factory()
        // ->count(3)
        // ->create();

        $casts = [
            [
                'name' => 'Brian Cox',
                'slug' => 'brian-cox',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.'
            ],
            [
                'name' => 'Coco König',
                'slug' => 'coco-konig',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.'
            ],
            [
                'name' => 'Emilia Fox',
                'slug' => 'emilia-fox',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.'
            ]
        ];

        foreach($casts as $cast){
            Cast::create($cast);
        }
    }
}
