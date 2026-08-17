<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Blog_tag;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class Blog_tagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tagIds = Tag::pluck('id');

        Blog::all()->each(function (Blog $blog) use ($tagIds) {
            $randomTagIds = $tagIds->random(min(5, $tagIds->count()));

            foreach ($randomTagIds as $tagId) {
                Blog_tag::factory()->create([
                    'blog_id' => $blog->id,
                    'tag_id' => $tagId,
                ]);
            }
        });
    }
}
