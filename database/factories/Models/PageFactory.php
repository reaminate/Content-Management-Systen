<?php

namespace Database\Factories\Models;

use App\Models\Image;
use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(200),
            'content'=> fake()->paragraph(3, true),
            'content_image' => $this->faker->randomElement(Image::where('for_author',false)->pluck('id')),
            'description' => fake()->text(100),
            'publication_status' => fake()->randomElement(['draft', 'published', 'archived']),
            'published_date' => fake()->optional(0.7)->date('y-m-d', 'now'),
            'SEO_title' => fake()->text(100),
            'SEO_description' => fake()->sentence(3, true),
        ];
    }
}
