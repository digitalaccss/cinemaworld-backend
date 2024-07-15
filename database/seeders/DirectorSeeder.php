<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Director;

class DirectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $directors = Director::factory()
        // ->count(3)
        // ->create();

        $directors = [
            [
                'name' => 'Theresa von Eltz',
                'slug' => 'theresa-von-eltz',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.'
            ],
            [
                'name' => 'Jessica Woodworth',
                'slug' => 'jessica-woodworth',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.'
            ],
            [
                'name' => 'János Edelényi',
                'slug' => 'janos-edelenyi',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry"s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.'
            ]
        ];

        foreach($directors as $director){
            Director::create($director);
        }
    }
}
