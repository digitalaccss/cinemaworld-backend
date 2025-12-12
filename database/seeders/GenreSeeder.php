<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Genre;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = [
            [
                'genre_display_name' => 'All Genres',
                'genre_name' => 'AllGenres'
            ],
            [
                'genre_display_name' => 'Family',
                'genre_name' => 'Family'
            ],
            [
                'genre_display_name' => 'Action & Adventure',
                'genre_name' => 'Action&Adventure'
            ],
            [
                'genre_display_name' => 'Comedy',
                'genre_name' => 'Comedy'
            ],
            [
                'genre_display_name' => 'Crime & Thriller',
                'genre_name' => 'Crime&Thriller'
            ],
            [
                'genre_display_name' => 'Drama',
                'genre_name' => 'Drama'
            ],
            [
                'genre_display_name' => 'Romance',
                'genre_name' => 'Romance'
            ],
            [
                'genre_display_name' => 'Sports',
                'genre_name' => 'Sports'
            ],
            [
                'genre_display_name' => 'Animation',
                'genre_name' => 'Animation'
            ]
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}
