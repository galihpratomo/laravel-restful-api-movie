<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'         => fake()->name(),
            'description'   => fake()->address(),
            'rating'        => fake()->unique(true)->numberBetween(1, 90),
            'image'         => fake()->image(public_path('storage/movie'),400,300, null, false),
        ];
    }
}
