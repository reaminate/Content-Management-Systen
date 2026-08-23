<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Image;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;
    //creates a simple blog
    public function test_creating_blog(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Image::factory()->create();
        Author::factory()->create();
        Image::factory()->create();
        Category::factory()->create();
        Tag::factory()->count(7)->create();
        $blog = Blog::factory()->make()->toArray();

        $response = $this->postJson('/api/blog', $blog);
        $response->assertStatus(201);
    }
    //trying to create a blog with invalid category
    public function test_valid_category_and_author_when_creating_blog():void
    {
        Sanctum::actingAs(User::factory()->create());
        Image::factory()->create();
        Author::factory()->create();
        Image::factory()->create();
        Category::factory()->create();
        $blog = Blog::factory([
            'category_id' => 1000000000,
            'author_id' => 200000000,
        ])->make()->toArray();
        $response = $this->postJson('/api/blog', $blog);
        $response->assertStatus(422);
    }
    //creates two tags and attaches them
    public function test_multiple_tags_can_be_attached():void
    {
        Sanctum::actingAs(User::factory()->create());
        Image::factory()->create();
        Author::factory()->create();
        Image::factory()->create();
        Category::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $blog = Blog::factory()->make()->toArray();
        $blog['tags'] = $tags->pluck('id')->toArray();

        $response = $this->postJson('/api/blog', $blog);
        $response->assertStatus(201);

        $createdBlog = Blog::first();
        $this->assertCount(2, $createdBlog->tags);
        $this->assertEqualsCanonicalizing(
            $tags->pluck('id')->toArray(),
            $createdBlog->tags->pluck('id')->toArray()
        );
    }
    //updates the created blogs author
    public function test_post_can_be_updated(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Image::factory()->create();
        Author::factory()->create();
        Image::factory()->create();
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $blog = Blog::factory([
            'category_id' => $category1->id,
        ])->create();
        $response = $this->putJson("api/blog/{$blog->slug}", [
            'category_id' => $category2->id,
        ]);
        $response->assertStatus(200);
    }
    //changes the tag relationships of the blog
    public function test_changing_the_tags_of_a_blog(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Image::factory()->create();
        Author::factory()->create();
        Image::factory()->create();
        Category::factory()->create();
        $tags = Tag::factory()->count(7)->create();

        $blog = Blog::factory()->create();
        $blog->tags()->sync($tags->take(2)->pluck('id'));

        $newTags = $tags->slice(2, 3);

        $response = $this->putJson("api/blog/{$blog->slug}", [
            'tags' => $newTags->pluck('id')->toArray(),
        ]);
        $response->assertStatus(200);

        $blog->refresh();
        $this->assertCount(3, $blog->tags);
        $this->assertEqualsCanonicalizing(
            $newTags->pluck('id')->toArray(),
            $blog->tags->pluck('id')->toArray()
        );
    }
    //deletes an existing blog
    public function test_post_deletion(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Image::factory()->create();
        Author::factory()->create();
        Image::factory()->create();
        Category::factory()->create();
        Tag::factory()->count(7)->create();
        $blog = Blog::factory()->create();
        $response = $this->deleteJson("api/blog/{$blog->slug}");
        $response->assertStatus(204);
    }
    //testing whether you can search using the blog slug
    public function test_search_post(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Image::factory()->create();
        Author::factory()->create();
        Image::factory()->create();
        Category::factory()->create();
        Tag::factory()->count(7)->create();
        $blog = Blog::factory()->create();
        $response = $this->getJson("api/blog/{$blog->slug}");
        $response->assertStatus(200);
    }
    public function test_the_category_and_status_filer():void
    {
        Sanctum::actingAs(User::factory()->create());
        Image::factory()->create();
        Author::factory()->create();
        Image::factory()->create();
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        Tag::factory()->count(7)->create();
        $blog1 = Blog::factory([
            'category_id' => $category1->id,
            'publication_status' => 'draft',
        ])->create();
        $blog2 = Blog::factory([
            'category_id' => $category1->id,
            'publication_status' => 'archived',
        ])->create();
        $blog3 = Blog::factory([
            'category_id' => $category2->id,
            'publication_status' => 'draft',
        ])->create();

        $response_category = $this->getJson('/api/blog?category_id=' . $category1->id);
        $response_category->assertStatus(200);
        $response_category->assertJsonCount(2, 'data');
        $ids = collect($response_category->json('data'))->pluck('id')->toArray();
        $this->assertEqualsCanonicalizing(
            [$blog1->id, $blog2->id],
            $ids
        );

        $response_status = $this->getJson('api/blog?publication_status=draft');
        $response_status->assertStatus(200);
        $response_status->assertJsonCount(2, 'data');
        $status = collect($response_status->json('data'))->pluck('id')->toArray();
        $this->assertEqualsCanonicalizing(
            [$blog1->id, $blog3->id],
            $status
        );
        
    }
}
