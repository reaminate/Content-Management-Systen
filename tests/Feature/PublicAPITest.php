<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Author;
use App\Models\Category;
use App\Models\Image;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublicAPITest extends TestCase
{
    use RefreshDatabase;
    //only published pages can be viewed
    public function test_published_blogs_view(): void
    {
        Image::factory()->create();
        Author::factory()->create();
        Image::factory()->create();
        Category::factory()->create();
        Tag::factory()->count(7)->create();
        $blog_published = Blog::factory([
            'publication_status' => 'published',
        ])->create();
        $response_published = $this->getJson("/blog/{$blog_published->slug}");
        $response_published->assertStatus(200);
        $blog_draft = Blog::factory([
            'publication_status' => 'draft',
        ])->create();
        $response_draft = $this->getJson("/blog/{$blog_draft->slug}");
        $response_draft->assertStatus(404);
        $blog_archived = Blog::factory([
            'publication_status' => 'archived',
        ])->create();
        $response_archived = $this->getJson("/blog/{$blog_archived->slug}");
        $response_archived->assertStatus(404);
    } 

}
