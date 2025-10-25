<?php
declare(strict_types=1);

namespace Tests\Feature\Payment;

use App\Models\CreditCard;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreditCardTest extends TestCase
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
        CreditCard::factory()->count(3)->create(['user_id' => $this->user->id]);
        
        $response = $this->getJson('/api/v1/payment/credit-cards');
        
        $response->assertOk()
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
                            'masked_number',
                            'formatted_expiration',
                            'is_expired'
                        ]
                    ]
                ],
                'message'
            ])
            ->assertJsonCount(3, 'data.list');
    }

    public function test_can_create_credit_card(): void
    {
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

        $response->assertCreated()
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

    public function test_can_update_credit_card(): void
    {
        $card = CreditCard::factory()->create(['user_id' => $this->user->id]);
        
        $data = [
            'id' => $card->id,
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'phone' => '9998887777',
            'card_number' => '5111222233334444',
            'security_code' => '321',
            'expiration_year' => '2027',
            'expiration_month' => '02',
            'zip' => '98765',
            'is_agree_to_receive' => false,
        ];

        $response = $this->putJson('/api/v1/payment/credit-cards', $data);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Credit card updated successfully'
            ]);

        $this->assertDatabaseHas('credit_cards', [
            'id' => $card->id,
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'type' => 'MasterCard',
        ]);
    }

    public function test_can_set_preferred_card(): void
    {
        $card1 = CreditCard::factory()->create(['user_id' => $this->user->id, 'is_preferred' => true]);
        $card2 = CreditCard::factory()->create(['user_id' => $this->user->id, 'is_preferred' => false]);

        $response = $this->postJson("/api/v1/payment/credit-cards/set-preferred/{$card2->id}");

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Credit card set as preferred successfully'
            ]);

        $this->assertFalse($card1->fresh()->is_preferred);
        $this->assertTrue($card2->fresh()->is_preferred);
    }

    public function test_can_delete_credit_card(): void
    {
        CreditCard::factory()->count(2)->create(['user_id' => $this->user->id]);
        $cardToDelete = $this->user->creditCards()->first();

        $response = $this->deleteJson("/api/v1/payment/credit-cards/{$cardToDelete->id}");

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Credit card deleted successfully'
            ]);

        $this->assertFalse($cardToDelete->fresh()->is_active);
    }

    public function test_cannot_delete_last_credit_card(): void
    {
        $card = CreditCard::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/v1/payment/credit-cards/{$card->id}");

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'message' => 'To remove, add another payment method first'
            ]);

        $this->assertTrue($card->fresh()->is_active);
    }

    public function test_requires_authentication(): void
    {
        Sanctum::actingAs(null);

        $response = $this->getJson('/api/v1/payment/credit-cards');

        $response->assertUnauthorized();
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
        $card = CreditCard::factory()->create(['user_id' => $this->user->id]);

        $response = $this->putJson('/api/v1/payment/credit-cards', [
            'id' => $card->id,
            // Missing required fields
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
        CreditCard::factory()->count(5)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/v1/payment/credit-cards?limit=2&page=1');

        $response->assertOk()
            ->assertJsonCount(2, 'data.list')
            ->assertJson([
                'data' => [
                    'count' => 5,
                    'page' => 1,
                    'page_size' => 2
                ]
            ]);
    }
}
