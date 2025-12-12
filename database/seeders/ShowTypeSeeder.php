<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ShowType;

class ShowTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $showTypes = [
            [
                'show_type' => 'Films'
            ],
            [
                'show_type' => 'Series'
            ],
            [
                'show_type' => 'Documentaries'
            ],
            [
                'show_type' => 'Shorts'
            ]
        ];

        foreach ($showTypes as $showType) {
            ShowType::create($showType);
        }
    }
}
