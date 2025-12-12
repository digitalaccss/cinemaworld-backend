<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cast>
 */
class CastFactory extends Factory
{
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // define an array of default attributes/cols that will be inserted into later using seeders' data
        return [
            'name' => $this->faker->firstName().' '.$this->faker->lastName(),
            'full_desc' => $this->faker->paragraph(2),
            'thumbnail_img_path' => $this->faker->imageUrl(360, 360)
        ];
    }
}
