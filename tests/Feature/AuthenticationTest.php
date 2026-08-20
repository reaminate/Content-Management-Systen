<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_login_succeeds(): void
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email', 'active_status'],
                'access_token',
                'token_type',
            ])
            ->assertJson([
                'user' => ['id' => $user->id, 'email' => $user->email],
                'token_type' => 'bearer',
            ]);
    }

    public function test_invalid_credentials_fail(): void
    {
        User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('error');
    }

    public function test_protected_endpoint_without_token_fails(): void
    {
        $response = $this->getJson('/api/logged');

        $response->assertStatus(401);
    }

    public function test_protected_endpoint_with_token_succeeds(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/logged');

        $response->assertStatus(200)
            ->assertJsonStructure(['users']);
    }

    public function test_current_user_endpoint_works(): void
    {

        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/logged');

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $user->id, 'email' => $user->email]);
    }

    public function test_logout_invalidates_current_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/logged')
            ->assertStatus(200);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/logout')
            ->assertStatus(200);

        Auth::forgetGuards();

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/logged')
            ->assertStatus(401);
    }
}
