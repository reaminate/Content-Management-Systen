<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;
    //creates a page to test
    public function test_page_can_be_created(): void
    {
        Sanctum::actingAs(User::factory(['is_admin'=>true])->create());
        Image::factory()->create();
        $page = Page::factory()->make();
        $response = $this->postJson('/api/page', [
            'title' => $page->title,
            'content' => $page->content,
            'content_image' => $page->image->id,
            'description' => $page->description,
            'SEO_title' => $page->SEO_title,
            'SEO_description' => $page->SEO_description,
        ]);
        $response->assertStatus(201);
    }
    //creates two pages with the same title. Slug is automatically generated via title
    public function test_title_and_slug_validation():void
    {
        Sanctum::actingAs(User::factory()->create());
        Image::factory()->create();
        Page::factory()->create([
            'title' => 'same'
        ]);
        $page_fake = Page::factory()->make([
            'title' => 'same'
        ]);
        $response = $this->postJson('/api/page', [
            'title' => $page_fake->title,
            'content' => $page_fake->content,
            'content_image' => $page_fake->image(),
            'description' => $page_fake->description,
            'SEO_title' => $page_fake->SEO_title,
            'SEO_description' => $page_fake->SEO_description,
        ]);
        $response->assertStatus(422);
    }
    //creates and retireves page
    public function test_page_retrieval():void
    {
        Sanctum::actingAs(User::factory()->create());
        Image::factory()->create();
        $page = Page::factory()->create();
        $response = $this->getJson("api/page/{$page->slug}");
        $response->assertStatus(200);
    }
    //updates the pages image
    public function test_page_update():void
    {
        Sanctum::actingAs(User::factory(['is_admin'=>true])->create());
        $image1 = Image::factory()->create();
        $image2 = Image::factory()->create();
        $page = Page::factory([
            'content_image' => $image1->id,
        ])->create();

        $response = $this->putJson("api/page/{$page->slug}", [
            'content_image' => $image2->id,
        ]);
        $response->assertStatus(200);
    }
    //creates and then deletes that page
    public function test_page_deletion():void
    {
        Sanctum::actingAs(User::factory(['is_admin'=>true])->create());
        Image::factory()->create();
        $page = Page::factory()->create();
        $response = $this->deleteJson("api/page/{$page->slug}");
        $response->assertStatus(204);
    }
    //checks whether only published pages are viewed publicly (without authentication)
    public function test_published_pages_appear_publicly_and_draft_doesnt():void
    {
        Image::factory()->create();
        $page_public = Page::factory([
            'publication_status' => 'published',
        ])->create();
        $page_draft = Page::factory([
            'publication_status' => 'draft',
        ])->create();
        $response = $this->getJson("/page/{$page_public->slug}");
        $response->assertStatus(200);
        $response = $this->getJson("/page/{$page_draft->slug}");
        $response->assertStatus(404);
    }
}
