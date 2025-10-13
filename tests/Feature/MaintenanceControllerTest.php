<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MaintenanceControllerTest extends TestCase
{
    /**
     * Test successful text summarization
     */
    public function test_summarize_returns_successful_response(): void
    {
        // Mock OpenAI API response
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'This is a summarized text.'
                        ]
                    ]
                ]
            ], 200)
        ]);

        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/maintenance/summarize', [
                'size' => 100,
                'text' => 'This is a very long text that needs to be summarized into a shorter version.'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'summarized' => 'This is a summarized text.'
                ]
            ]);
    }

    /**
     * Test validation errors for missing fields
     */
    public function test_summarize_validates_required_fields(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/maintenance/summarize', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['size', 'text']);
    }

    /**
     * Test validation errors for invalid size
     */
    public function test_summarize_validates_size_constraints(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/maintenance/summarize', [
                'size' => 0,
                'text' => 'Some text'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['size']);
    }

    /**
     * Test validation errors for text too long
     */
    public function test_summarize_validates_text_length(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/maintenance/summarize', [
                'size' => 100,
                'text' => str_repeat('a', 50001) // Too long
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['text']);
    }

    /**
     * Test unauthenticated request
     */
    public function test_summarize_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/maintenance/summarize', [
            'size' => 100,
            'text' => 'Some text'
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test OpenAI API error handling
     */
    public function test_summarize_handles_openai_api_error(): void
    {
        // Mock OpenAI API error response
        Http::fake([
            'api.openai.com/*' => Http::response([
                'error' => 'API key invalid'
            ], 401)
        ]);

        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/maintenance/summarize', [
                'size' => 100,
                'text' => 'Some text'
            ]);

        $response->assertStatus(500)
            ->assertJson([
                'success' => false,
                'message' => 'Failed to summarize text'
            ]);
    }

    /**
     * Test missing OpenAI API key
     */
    public function test_summarize_handles_missing_api_key(): void
    {
        // Set empty API key
        config(['services.openai.api_key' => null]);

        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/maintenance/summarize', [
                'size' => 100,
                'text' => 'Some text'
            ]);

        $response->assertStatus(500)
            ->assertJson([
                'success' => false,
                'message' => 'OpenAI API key not configured'
            ]);
    }
}