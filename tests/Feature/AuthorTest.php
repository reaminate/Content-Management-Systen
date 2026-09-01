<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_whether_author_profile_pic_update_works(): void
    {
        Sanctum::actingAs(User::factory(['is_admin'=>true])->create());
        $image1 = Image::factory()->create();
        $image2 = Image::factory()->create();
        $author = Author::factory([
            'profile_pic' => $image1->id,
        ])->create();
        $this->assertDatabaseHas('images', [
            'id' => $image1->id,
            'for_author' => true,
        ]);
        $response = $this->putJson("api/author/{$author->slug}", [
            'profile_pic' => $image2->id,
        ]);
        $this->getJson(('api/notifications'))->assertStatus(200)->assertJsonCount(3, 'data');
        $response->assertStatus(200);
        $this->assertDatabaseHas('images', [
            'id' => $image1->id,
            'for_author' => false,
        ]);
        $this->assertDatabaseHas('images', [
            'id' => $image2->id,
            'for_author' => true,
        ]);
        
    }
}
