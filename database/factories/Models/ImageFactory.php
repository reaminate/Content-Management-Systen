<?php

namespace Database\Factories\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fixture = fake()->randomElement(
            glob(base_path('tests/Fixtures/images') . '/*.{jpg,jpeg,png}', GLOB_BRACE)
        );

        return [
            'original_filename' => basename($fixture),
            'stored_filename' => fake()->text(50),
            'file_path' => $fixture,
            'file_type' => fake()->randomElement(['image/jpeg', 'image/png', 'image/jpg']),
            'filesize' => fake()->numberBetween(1000, 500000),
            'caption' => fake()->text(50),
            'upload_date' => fake()->date('Y-m-d', 'now'),
        ];
    }
}
