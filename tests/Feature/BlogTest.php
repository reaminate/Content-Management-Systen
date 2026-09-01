<?php

namespace Tests\Feature;

use App\Jobs\SummarizePost;
use App\Models\Author;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Image;
use App\Models\Tag;
use App\Models\User;
use App\Notifications\BlogCreated;
use App\Notifications\BlogDeleted;
use App\Notifications\BlogUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;
    //creates a simple blog
    public function test_creating_blog(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        Image::factory()->create();
        Author::factory()->create();
        Image::factory()->create();
        Category::factory()->create();
        Tag::factory()->count(7)->create();
        $blog = Blog::factory(['publication_status' => 'published'])->make()->toArray();
        Notification::fake();
        Queue::fake();
        $response = $this->postJson('/api/blog', $blog);
        Notification::assertSentTo([$user], BlogCreated::class);
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
    //updates the created blogs category (checks with differnt users)
    public function test_post_can_be_updated(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);  
        Image::factory()->create();
        $author = Author::factory([
            'user_id' => $user->id,
        ])->create();
        Image::factory()->create();
        Notification::fake();
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $blog = Blog::factory([
            'author_id' => $author->id,
            'category_id' => $category1->id,
        ])->create();
        //updating the blog as the author
        $response = $this->putJson("api/blog/{$blog->slug}", [
            'category_id' => $category2->id,
        ]);
        $response->assertStatus(200);
        Notification::fake();

        //trying to update the same blog but as a different author with different user
        $user2 = User::factory()->create();
        Image::factory()->create();
        $author = Author::factory([
            'user_id' => $user2->id,
        ])->create();
        Sanctum::actingAs($user2);
        $response = $this->putJson("api/blog/{$blog->slug}", [
            'category_id' => $category1->id,
        ]);
        $response->assertStatus(403);
        Notification::assertNothingSent();
        //trying to update that same blog but as differnet user 
        $user3 = User::factory()->create();
        Image::factory()->create();
        Sanctum::actingAs($user3);
        $response = $this->putJson("api/blog/{$blog->slug}", [
            'category_id' => $category2->id,
        ]);
        $response->assertStatus(403);
        Notification::assertNothingSent();
    }
    //changes the tag relationships of the blog
    public function test_changing_the_tags_of_a_blog(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        Image::factory()->create();
        Author::factory([
            'user_id' => $user->id,
        ])->create();
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
    //deletes an existing blog (takes into account if you made the blog or not)
    public function test_post_deletion(): void
    {
        $user1 = User::factory()->create();
        Image::factory()->create();
        $author1 = Author::factory([
            'user_id' => $user1->id,
        ])->create();
        $user2 = User::factory()->create();
        Image::factory()->create();
        $author2 = Author::factory([
            'user_id' => $user2->id,
        ])->create();

        Image::factory()->create();
        Author::factory()->create();
        Image::factory()->create();
        Category::factory()->create();
        Tag::factory()->count(7)->create();
        $blog = Blog::factory([
            'author_id' => $author1->id,
        ])->create();

        //should fail as your logged in as not the author who made the blog
        Sanctum::actingAs($user2);
        $response = $this->deleteJson("api/blog/{$blog->slug}");
        $response->assertStatus(403);

        //should work as you made the blog
        Sanctum::actingAs($user1);
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

    public function test_whether_an_admin_can_delete_any_blog():void
    {
        $user_admin = User::factory([
            'is_admin' => true,
        ])->create();
        $user = User::factory()->create();
        Image::factory()->create();
        Author::factory([
            'user_id' => null, 
        ])->create();
        Image::factory()->create();
        Category::factory()->create();
        Tag::factory()->count(7)->create();
        $blog = Blog::factory()->create();
        Notification::fake();
        //should fail as you are not a logged in author
        Sanctum::actingAs($user); //need to be logged to view anything
        $response = $this->deleteJson("api/blog/{$blog->slug}");
        $response->assertStatus(403);
        Notification::assertNothingSent();
        //should pass as you are the admin
        Sanctum::actingAs($user_admin); 
        $response = $this->deleteJson("api/blog/{$blog->slug}");
        $response->assertStatus(204);
        Notification::assertNotSentTo([$user_admin], BlogDeleted::class);
    }

    //deletes a soft deleted model (blog)
    public function test_whether_only_admin_can_fully_delete_a_model():void
    {
        $user_admin = User::factory([
            'is_admin' => true,
        ])->create();
        $user = User::factory()->create();
        Image::factory()->create();
        Author::factory([
            'user_id' => $user->id, 
        ])->create();
        Image::factory()->create();
        Category::factory()->create();
        Tag::factory()->count(7)->create();
        $blog = Blog::factory()->create();
        //soft delete the blog
        Sanctum::actingAs($user);
        $response = $this->deleteJson("api/blog/{$blog->slug}");
        $response->assertStatus(204);

        //trying to fully delete as non admin
        $response = $this->deleteJson("api/delete/1", ['blogs'=> true]);
        $response->assertStatus(403);

        //trying to fully delete as an admin
        Sanctum::actingAs($user_admin);
        $response = $this->deleteJson("api/delete/1", ['blogs'=> true]);
        $response->assertStatus(204);

    }
}
