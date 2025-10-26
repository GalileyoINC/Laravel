<?php

declare(strict_types=1);

namespace Tests\Feature\Payment;

use App\Models\CreditCard;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * PaymentControllerTest
 * Feature tests for PaymentController API endpoints
 */
class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_can_get_credit_cards(): void
    {
        // Create test credit cards
        CreditCard::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/payment/credit-cards');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'count',
                    'page',
                    'page_size',
                    'list' => [
                        '*' => [
                            'id',
                            'first_name',
                            'last_name',
                            'num',
                            'type',
                            'expiration_year',
                            'expiration_month',
                            'is_preferred',
                            'created_at',
                            'phone',
                            'zip',
                            'is_agree_to_receive',
                        ]
                    ]
                ]
            ]);

        $this->assertEquals(3, $response->json('data.count'));
        $this->assertCount(3, $response->json('data.list'));
    }

    public function test_can_create_credit_card(): void
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'card_number' => '4111111111111111',
            'security_code' => '123',
            'expiration_year' => '2025',
            'expiration_month' => '12',
            'zip' => '12345',
            'is_agree_to_receive' => true,
        ];

        $response = $this->postJson('/api/v1/payment/credit-cards', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'num',
                    'type',
                    'expiration_year',
                    'expiration_month',
                    'is_preferred',
                    'created_at',
                    'phone',
                    'zip',
                    'is_agree_to_receive',
                ],
                'message'
            ]);

        $this->assertEquals('success', $response->json('status'));
        $this->assertEquals('Credit card created successfully', $response->json('message'));
        $this->assertEquals('John', $response->json('data.first_name'));
        $this->assertEquals('Doe', $response->json('data.last_name'));
        $this->assertEquals('****1111', $response->json('data.num')); // Should be masked
        $this->assertEquals('Visa', $response->json('data.type'));
        $this->assertTrue($response->json('data.is_preferred')); // First card should be preferred

        // Verify card was created in database
        $this->assertDatabaseHas('credit_cards', [
            'user_id' => $this->user->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'num' => '****1111',
            'type' => 'Visa',
            'expiration_year' => 2025,
            'expiration_month' => 12,
            'zip' => '12345',
            'is_active' => true,
            'is_preferred' => true,
            'is_agree_to_receive' => true,
        ]);
    }

    public function test_can_update_credit_card(): void
    {
        $card = CreditCard::factory()->create([
            'user_id' => $this->user->id,
            'first_name' => 'Old',
            'last_name' => 'Name',
        ]);

        $data = [
            'id' => $card->id,
            'first_name' => 'New',
            'last_name' => 'Name',
            'phone' => '+9876543210',
            'card_number' => '5555555555554444',
            'security_code' => '456',
            'expiration_year' => '2026',
            'expiration_month' => '06',
            'zip' => '54321',
            'is_agree_to_receive' => false,
        ];

        $response = $this->putJson('/api/v1/payment/credit-cards', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'num',
                    'type',
                    'expiration_year',
                    'expiration_month',
                    'is_preferred',
                    'created_at',
                    'phone',
                    'zip',
                    'is_agree_to_receive',
                ],
                'message'
            ]);

        $this->assertEquals('success', $response->json('status'));
        $this->assertEquals('Credit card updated successfully', $response->json('message'));
        $this->assertEquals('New', $response->json('data.first_name'));
        $this->assertEquals('Name', $response->json('data.last_name'));
        $this->assertEquals('****4444', $response->json('data.num')); // Should be masked
        $this->assertEquals('MasterCard', $response->json('data.type'));

        // Verify card was updated in database
        $this->assertDatabaseHas('credit_cards', [
            'id' => $card->id,
            'user_id' => $this->user->id,
            'first_name' => 'New',
            'last_name' => 'Name',
            'phone' => '+9876543210',
            'num' => '****4444',
            'type' => 'MasterCard',
            'expiration_year' => 2026,
            'expiration_month' => 6,
            'zip' => '54321',
            'is_agree_to_receive' => false,
        ]);
    }

    public function test_can_set_preferred_card(): void
    {
        // Create multiple cards
        $card1 = CreditCard::factory()->create([
            'user_id' => $this->user->id,
            'is_preferred' => true,
        ]);
        
        $card2 = CreditCard::factory()->create([
            'user_id' => $this->user->id,
            'is_preferred' => false,
        ]);

        $response = $this->postJson('/api/v1/payment/credit-cards/set-preferred', [
            'id' => $card2->id
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message'
            ]);

        $this->assertEquals('success', $response->json('status'));
        $this->assertEquals('Preferred card set successfully', $response->json('message'));

        // Verify cards were updated in database
        $this->assertDatabaseHas('credit_cards', [
            'id' => $card1->id,
            'is_preferred' => false,
        ]);
        
        $this->assertDatabaseHas('credit_cards', [
            'id' => $card2->id,
            'is_preferred' => true,
        ]);
    }

    public function test_can_delete_credit_card(): void
    {
        // Create multiple cards
        $card1 = CreditCard::factory()->create([
            'user_id' => $this->user->id,
            'is_preferred' => true,
        ]);
        
        $card2 = CreditCard::factory()->create([
            'user_id' => $this->user->id,
            'is_preferred' => false,
        ]);

        $response = $this->deleteJson('/api/v1/payment/credit-cards', [
            'id' => $card1->id
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message'
            ]);

        $this->assertEquals('success', $response->json('status'));
        $this->assertEquals('Credit card deleted successfully', $response->json('message'));

        // Verify card was soft deleted in database
        $this->assertDatabaseHas('credit_cards', [
            'id' => $card1->id,
            'is_active' => false,
        ]);

        // Verify other card is now preferred
        $this->assertDatabaseHas('credit_cards', [
            'id' => $card2->id,
            'is_preferred' => true,
        ]);
    }

    public function test_cannot_delete_last_active_card(): void
    {
        $card = CreditCard::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->deleteJson('/api/v1/payment/credit-cards', [
            'id' => $card->id
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'status',
                'message'
            ]);

        $this->assertEquals('error', $response->json('status'));
        $this->assertEquals('To remove, add another payment method first', $response->json('message'));
    }

    public function test_cannot_set_inactive_card_as_preferred(): void
    {
        $card = CreditCard::factory()->create([
            'user_id' => $this->user->id,
            'is_active' => false,
            'is_preferred' => false,
        ]);

        $response = $this->postJson('/api/v1/payment/credit-cards/set-preferred', [
            'id' => $card->id
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'status',
                'message'
            ]);

        $this->assertEquals('error', $response->json('status'));
        $this->assertEquals('Card is not active', $response->json('message'));
    }

    public function test_requires_authentication(): void
    {
        // Clear authentication
        $this->app['auth']->forgetGuards();

        $response = $this->getJson('/api/v1/payment/credit-cards');

        $response->assertStatus(401);
    }

    public function test_validation_errors_for_create_credit_card(): void
    {
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

    public function test_validation_errors_for_update_credit_card(): void
    {
        $card = CreditCard::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->putJson('/api/v1/payment/credit-cards', [
            'id' => $card->id,
        ]);

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

    public function test_can_get_credit_cards_with_pagination(): void
    {
        // Create more cards than the default limit
        CreditCard::factory()->count(15)->create([
            'user_id' => $this->user->id,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/payment/credit-cards?limit=10&page=2');

        $response->assertStatus(200);
        $this->assertEquals(15, $response->json('data.count'));
        $this->assertEquals(2, $response->json('data.page'));
        $this->assertEquals(10, $response->json('data.page_size'));
        $this->assertCount(5, $response->json('data.list')); // Remaining 5 cards
    }
}
