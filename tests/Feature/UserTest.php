<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    public function test_to_see_if_only_admins_can_make_a_user_admin()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $user_admin = User::factory([
            'is_admin' => true,
        ])->create();
        //should fail as logged in not as an admin
        Sanctum::actingAs($user1);
        $response = $this->putJson("/api/user/{$user2->id}/admin", ['is_admin'=>true]);
        $response->assertStatus(403);

        //should pass as now youre an admin
        Sanctum::actingAs($user_admin);
        $response = $this->putJson("/api/user/{$user2->id}/admin", ['is_admin'=>true]);
        $response->assertStatus(200);
    }
}
