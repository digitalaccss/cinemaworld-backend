<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Film>
 */
class FilmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'foreign_title' => $this->faker->word(),
            'release_year' => $this->faker->year(),
            'runtime' => $this->faker->time('i'),
            'short_desc' => $this->faker->paragraph(1),
            'full_desc' => $this->faker->paragraph(2),
            'trivia_desc' => $this->faker->paragraph(1),
            'trailer_link_url' => 'https://www.youtube.com/watch?v=cRy3Pz_ZVWA'
        ];
    }
}
