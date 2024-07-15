<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Accolade;

class AccoladeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accolades = [
            [
                'name' => 'Venice Film Festival, 2016',
                'category' => 'Best Film Nominee'
            ],
            [
                'name' => 'Antalya Golden Orange Film Festival, 2016',
                'category' => 'Best Film Nominee, Golden Orange'
            ],
            [
                'name' => 'German Film Awards, 2016',
                'category' => 'Outstanding Feature Film, Film Award'
            ]
        ];

        foreach($accolades as $accolade){
            Accolade::create($accolade);
        }
    }
}
