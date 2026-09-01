<?php

namespace Tests\Feature;

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
        }

        $response = $this->getJson('api/notifications');
        $response->assertStatus(200);
        $response->assertJsonCount(8, 'data');
    }
}
