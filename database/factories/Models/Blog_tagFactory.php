<?php

namespace Database\Factories\Models;

use App\Models\Blog;
use App\Models\Blog_tag;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Blog_tag>
 */
class Blog_tagFactory extends Factory
{
    protected $model = Blog_tag::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'blog_id' => Blog::factory(),
            'tag_id' => Tag::factory(),
        ];
    }
}
