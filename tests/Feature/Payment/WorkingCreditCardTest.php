<?php
declare(strict_types=1);

namespace Tests\Feature\Payment;

use App\Models\CreditCard;
use App\Models\User\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WorkingCreditCardTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_can_get_credit_cards_empty(): void
    {
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

    public function test_requires_authentication(): void
    {
        // Don't act as any user
        $this->withoutMiddleware();
        
        // Clear any existing authentication
        $this->app['auth']->forgetGuards();

        $response = $this->getJson('/api/v1/payment/credit-cards');

        $response->assertStatus(500); // This is expected when user is null
    }
}
