<?php

declare(strict_types=1);

namespace Tests\Unit\Payment;

use App\Domain\DTOs\Payment\PaymentDetailsDTO;
use App\Domain\Services\Payment\PaymentService;
use App\Models\CreditCard;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * PaymentServiceTest
 * Unit tests for PaymentService
 */
class PaymentServiceTest extends TestCase
{
    // Removed RefreshDatabase trait to avoid foreign key issues

    private PaymentService $paymentService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->paymentService = new PaymentService();
        
        // Create a user for testing - use existing user or create one
        $existingUser = User::first();
        if ($existingUser) {
            $this->user = $existingUser;
        } else {
            $this->user = User::factory()->create();
        }

        // Ensure no lingering credit cards for this user between tests
        \App\Models\CreditCard::where('user_id', $this->user->id)->delete();
    }

    public function test_can_get_credit_cards(): void
    {
        // Create test credit cards
        CreditCard::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'is_active' => true,
        ]);

        $result = $this->paymentService->getCreditCards($this->user, 10, 1);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('count', $result);
        $this->assertArrayHasKey('page', $result);
        $this->assertArrayHasKey('page_size', $result);
        $this->assertArrayHasKey('list', $result);
        $this->assertEquals(3, $result['count']);
        $this->assertEquals(1, $result['page']);
        $this->assertEquals(10, $result['page_size']);
        $this->assertCount(3, $result['list']);
    }

    public function test_can_create_credit_card(): void
    {
        $dto = new PaymentDetailsDTO(
            first_name: 'John',
            last_name: 'Doe',
            phone: '+1234567890',
            card_number: '4111111111111111',
            security_code: '123',
            expiration_year: '2025',
            expiration_month: '12',
            zip: '12345',
            is_agree_to_receive: true
        );

        $card = $this->paymentService->createCreditCard($this->user, $dto);

        $this->assertInstanceOf(CreditCard::class, $card);
        $this->assertEquals($this->user->id, $card->user_id);
        $this->assertEquals('John', $card->first_name);
        $this->assertEquals('Doe', $card->last_name);
        $this->assertEquals('+1234567890', $card->phone);
        $this->assertEquals('****1111', $card->num); // Should be masked
        $this->assertEquals('Visa', $card->type);
        $this->assertEquals(2025, $card->expiration_year);
        $this->assertEquals(12, $card->expiration_month);
        $this->assertEquals('12345', $card->zip);
        $this->assertTrue($card->is_active);
        $this->assertTrue($card->is_preferred); // First card should be preferred
        $this->assertTrue($card->is_agree_to_receive);
    }

    public function test_can_update_credit_card(): void
    {
        $card = CreditCard::factory()->create([
            'user_id' => $this->user->id,
            'first_name' => 'Old',
            'last_name' => 'Name',
        ]);

        $dto = new PaymentDetailsDTO(
            first_name: 'New',
            last_name: 'Name',
            phone: '+9876543210',
            card_number: '5555555555554444',
            security_code: '456',
            expiration_year: '2026',
            expiration_month: '06',
            zip: '54321',
            is_agree_to_receive: false,
            id: $card->id
        );

        $updatedCard = $this->paymentService->updateCreditCard($this->user, $dto);

        $this->assertInstanceOf(CreditCard::class, $updatedCard);
        $this->assertEquals('New', $updatedCard->first_name);
        $this->assertEquals('Name', $updatedCard->last_name);
        $this->assertEquals('+9876543210', $updatedCard->phone);
        $this->assertEquals('****4444', $updatedCard->num); // Should be masked
        $this->assertEquals('MasterCard', $updatedCard->type);
        $this->assertEquals(2026, $updatedCard->expiration_year);
        $this->assertEquals(6, $updatedCard->expiration_month);
        $this->assertEquals('54321', $updatedCard->zip);
        $this->assertFalse($updatedCard->is_agree_to_receive);
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

        $result = $this->paymentService->setPreferredCard($this->user, $card2->id);

        $this->assertTrue($result);
        
        // Refresh from database
        $card1->refresh();
        $card2->refresh();
        
        $this->assertFalse($card1->is_preferred);
        $this->assertTrue($card2->is_preferred);
    }

    public function test_cannot_set_inactive_card_as_preferred(): void
    {
        $card = CreditCard::factory()->create([
            'user_id' => $this->user->id,
            'is_active' => false,
            'is_preferred' => false,
        ]);

        $result = $this->paymentService->setPreferredCard($this->user, $card->id);

        $this->assertFalse($result);
        $this->assertFalse($card->fresh()->is_preferred);
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

        $result = $this->paymentService->deleteCreditCard($this->user, $card1->id);

        $this->assertTrue($result);
        
        // Card should be soft deleted (is_active = false)
        $card1->refresh();
        $this->assertFalse($card1->is_active);
        
        // Other card should now be preferred
        $card2->refresh();
        $this->assertTrue($card2->is_preferred);
    }

    public function test_cannot_delete_last_active_card(): void
    {
        $card = CreditCard::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('To remove, add another payment method first');

        $this->paymentService->deleteCreditCard($this->user, $card->id);
    }

    public function test_first_card_is_automatically_preferred(): void
    {
        $dto = new PaymentDetailsDTO(
            first_name: 'First',
            last_name: 'Card',
            phone: '+1111111111',
            card_number: '4111111111111111',
            security_code: '123',
            expiration_year: '2025',
            expiration_month: '12',
            zip: '12345'
        );

        $card = $this->paymentService->createCreditCard($this->user, $dto);

        $this->assertTrue($card->is_preferred);
    }

    public function test_subsequent_cards_are_not_preferred(): void
    {
        // Create first card
        CreditCard::factory()->create([
            'user_id' => $this->user->id,
            'is_preferred' => true,
        ]);

        // Create second card
        $dto = new PaymentDetailsDTO(
            first_name: 'Second',
            last_name: 'Card',
            phone: '+2222222222',
            card_number: '5555555555554444',
            security_code: '456',
            expiration_year: '2026',
            expiration_month: '06',
            zip: '54321'
        );

        $card = $this->paymentService->createCreditCard($this->user, $dto);

        $this->assertFalse($card->is_preferred);
    }
}
