<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ActiveTag;

class ActiveTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activeTags = [
            [
                'films' => ["1"],
                'tag_id' => 2,
            ],
            [
                'films' => ["1", "2"],
                'tag_id' => 3,
            ],
            [
                'films' => ["2", "3"],
                'tag_id' => 6,
            ],
            [
                'films' => ["1", "3"],
                'tag_id' => 4,
            ],
            [
                'films' => ["3"],
                'tag_id' => 5,
            ],
            [
                'films' => ["2"],
                'tag_id' => 7,
            ],
        ];

        foreach($activeTags as $activeTag){
            ActiveTag::create($activeTag);
        }
    }
}
