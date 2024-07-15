<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Critic;

class CriticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $critics = [
            [
                'name' => 'Variety',
                'critique' => "...'King of the Belgians' is a delightful, surprisingly respectful ribbing of the incongruity of monarchy, Belgium, and the Balkans.",
                'show_id' => 3
            ],
            [
                'name' => 'The Hollywood Reporter',
                'critique' => '...Flemish actor Van den Begin is an inspired choice to play Nicolas III...',
                'show_id' => 4
            ],
            [
                'name' => 'Screen Daily',
                'critique' => '...attractive performance from lead actor Lazaro Ramos and a rich soundtrack that ranges from rap to Paganini.',
                'show_id' => 6
            ]
        ];

        foreach($critics as $critic){
            Critic::create($critic);
        }
    }
}
