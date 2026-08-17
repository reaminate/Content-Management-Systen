<?php

namespace Database\Factories\Models;

use App\Models\Author;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    protected $model = Author::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email'=> fake()->unique()->safeEmail(),
            'short_biography' => fake()->realTextBetween(100, 500),
            'profile_pic'=> $this->faker->randomElement(Image::where('for_author', false)->pluck('id')),
            'active' => fake()->boolean(70)
        ];
    }
    public function configure(): static
    {
        return $this->afterCreating(function (Author $author) {
            $author->image->update(['for_author' => true]);
        });
    }

}
