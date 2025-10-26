<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password'),
            'role' => 2,
            'status' => 1,
        ]);

        Sanctum::actingAs($this->user);
    }

    public function test_can_get_conversation_list(): void
    {
        $response = $this->postJson('/api/v1/chat/list', [
            'page' => 1,
            'per_page' => 15,
        ]);

        $response->assertStatus(200);
    }

    public function test_requires_authentication(): void
    {
        // Clear authentication
        $this->app['auth']->forgetGuards();

        $response = $this->postJson('/api/v1/chat/list', [
            'page' => 1,
            'per_page' => 15,
        ]);

        $response->assertStatus(401);
    }
}
