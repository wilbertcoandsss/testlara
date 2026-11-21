<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // attribute + fakernya
            'name' => fake()->words(2, true),
            'caption' => fake()->word(),
            'likes' => fake()->numberBetween(10, 100),
            'is_archived' => false,
            'user_id' => fake()->numberBetween(1, 3)
        ];
    }
}
