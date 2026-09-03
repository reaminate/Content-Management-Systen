<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Image;
use App\Models\Page;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_notification_que_works(): void
    {
        $user = User::factory(['is_admin'=>true])->create();
        Sanctum::actingAs($user);
        Image::factory()->count(4)->create();
        for ($i = 0; $i < 4; $i++) {
            $this->postJson('api/page', Page::factory()->make()->toArray())
                ->assertStatus(201);
            $this->postJson('api/tag', Tag::factory()->make()->toArray())
                ->assertStatus(201);
            $this->postJson('api/category', Category::factory()->make()->toArray())
                ->assertStatus(201);
        }
        //only the person that made those gets the notifications
        Sanctum::actingAs(User::factory()->create());
        $response = $this->getJson('api/notifications');
        $response->assertJsonCount(0);

        Sanctum::actingAs($user);
        $response = $this->getJson('api/notifications');
        $response->assertStatus(200);
        $response->assertJsonCount(12);
        //marked as read, next time its accessed, no notifications should be there
        $response = $this->getJson('api/notifications');
        $response->assertJsonCount(0);
    }
}
