<?php
declare(strict_types=1);

namespace Tests\Feature\Payment;

use App\Models\CreditCard;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SimpleCreditCardTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_get_credit_cards_empty(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->getJson('/api/v1/payment/credit-cards');
        
        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'data' => [
                    'count',
                    'page',
                    'page_size',
                    'list'
                ]
            ])
            ->assertJsonCount(0, 'data.list')
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'count' => 0,
                    'page' => 1,
                    'page_size' => 100
                ]
            ]);
    }

    public function test_can_create_credit_card(): void
    {
        Sanctum::actingAs($this->user);
        $data = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '1234567890',
            'card_number' => '4111222233334444',
            'security_code' => '123',
            'expiration_year' => '2025',
            'expiration_month' => '12',
            'zip' => '12345',
            'is_agree_to_receive' => true,
        ];

        $response = $this->postJson('/api/v1/payment/credit-cards', $data);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Credit card created successfully'
            ]);

        $this->assertDatabaseHas('credit_cards', [
            'user_id' => $this->user->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'type' => 'Visa',
            'is_preferred' => true, // First card should be preferred
        ]);
    }

    public function test_validation_errors_for_create_credit_card(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson('/api/v1/payment/credit-cards', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name',
                'last_name',
                'phone',
                'card_number',
                'security_code',
                'expiration_year',
                'expiration_month',
                'zip'
            ]);
    }

    public function test_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/payment/credit-cards');

        $response->assertUnauthorized();
    }
}
