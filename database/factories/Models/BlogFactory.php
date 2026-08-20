<?php

namespace Database\Factories\Models;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Image;
use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Blog>
 */
class BlogFactory extends Factory
{
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $published_date = fake()->optional(0.8)->date('Y-m-d', 'now');
        if(isset($published_date)){
            $publication_status = 'published';
        }else{
            $publication_status = fake()->randomElement(['draft', 'archived']);
        }
        return [
            'title'=>fake()->unique()->sentence(20, true),
            'category_id'=>$this->faker->randomElement(Category::pluck('id')),
            'excerpt' => fake()->sentence(10, true),
            'content' => fake()->realTextBetween(20, 30),
            'image_id'=> $this->faker->randomElement(Image::where('for_author', false)->pluck('id')),
            'author_id' => $this->faker->randomElement(Author::pluck('id')),
            'published_at' => $published_date,
            'publication_status' => $publication_status,
        ];
    }
}
