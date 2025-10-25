<?php

declare(strict_types=1);

namespace Tests\Feature\Payment;

use App\Models\CreditCard;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * PaymentIntegrationTest
 * Integration tests for complete payment flow
 */
class PaymentIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_complete_payment_method_lifecycle(): void
    {
        // 1. Create first credit card
        $createResponse = $this->postJson('/api/v1/payment/credit-cards', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'card_number' => '4111111111111111',
            'security_code' => '123',
            'expiration_year' => '2025',
            'expiration_month' => '12',
            'zip' => '12345',
            'is_agree_to_receive' => true,
        ]);

        $createResponse->assertStatus(200);
        $cardId = $createResponse->json('data.id');
        $this->assertTrue($createResponse->json('data.is_preferred')); // First card should be preferred

        // 2. Create second credit card
        $createResponse2 = $this->postJson('/api/v1/payment/credit-cards', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => '+0987654321',
            'card_number' => '5555555555554444',
            'security_code' => '456',
            'expiration_year' => '2026',
            'expiration_month' => '06',
            'zip' => '54321',
            'is_agree_to_receive' => false,
        ]);

        $createResponse2->assertStatus(200);
        $cardId2 = $createResponse2->json('data.id');
        $this->assertFalse($createResponse2->json('data.is_preferred')); // Second card should not be preferred

        // 3. Get all credit cards
        $listResponse = $this->getJson('/api/v1/payment/credit-cards');
        $listResponse->assertStatus(200);
        $this->assertEquals(2, $listResponse->json('data.count'));
        $this->assertCount(2, $listResponse->json('data.list'));

        // Verify first card is still preferred
        $firstCard = collect($listResponse->json('data.list'))->firstWhere('id', $cardId);
        $this->assertTrue($firstCard['is_preferred']);

        // 4. Set second card as preferred
        $setPreferredResponse = $this->postJson('/api/v1/payment/credit-cards/set-preferred', [
            'id' => $cardId2
        ]);
        $setPreferredResponse->assertStatus(200);

        // 5. Verify preference change
        $listResponse2 = $this->getJson('/api/v1/payment/credit-cards');
        $listResponse2->assertStatus(200);

        $firstCardUpdated = collect($listResponse2->json('data.list'))->firstWhere('id', $cardId);
        $secondCardUpdated = collect($listResponse2->json('data.list'))->firstWhere('id', $cardId2);
        
        $this->assertFalse($firstCardUpdated['is_preferred']);
        $this->assertTrue($secondCardUpdated['is_preferred']);

        // 6. Update first card
        $updateResponse = $this->putJson('/api/v1/payment/credit-cards', [
            'id' => $cardId,
            'first_name' => 'Johnny',
            'last_name' => 'Updated',
            'phone' => '+1111111111',
            'card_number' => '4000000000000002',
            'security_code' => '789',
            'expiration_year' => '2027',
            'expiration_month' => '03',
            'zip' => '11111',
            'is_agree_to_receive' => true,
        ]);
        $updateResponse->assertStatus(200);
        $this->assertEquals('Johnny', $updateResponse->json('data.first_name'));
        $this->assertEquals('Updated', $updateResponse->json('data.last_name'));

        // 7. Delete first card
        $deleteResponse = $this->deleteJson('/api/v1/payment/credit-cards', [
            'id' => $cardId
        ]);
        $deleteResponse->assertStatus(200);

        // 8. Verify only one card remains and it's preferred
        $finalListResponse = $this->getJson('/api/v1/payment/credit-cards');
        $finalListResponse->assertStatus(200);
        $this->assertEquals(1, $finalListResponse->json('data.count'));
        $this->assertCount(1, $finalListResponse->json('data.list'));
        
        $remainingCard = $finalListResponse->json('data.list.0');
        $this->assertEquals($cardId2, $remainingCard['id']);
        $this->assertTrue($remainingCard['is_preferred']); // Should be preferred after deletion

        // 9. Try to delete last card (should fail)
        $deleteLastResponse = $this->deleteJson('/api/v1/payment/credit-cards', [
            'id' => $cardId2
        ]);
        $deleteLastResponse->assertStatus(400);
        $this->assertEquals('To remove, add another payment method first', $deleteLastResponse->json('message'));
    }

    public function test_payment_method_pagination(): void
    {
        // Create 15 credit cards
        CreditCard::factory()->count(15)->create([
            'user_id' => $this->user->id,
            'is_active' => true,
        ]);

        // Test first page
        $page1Response = $this->getJson('/api/v1/payment/credit-cards?limit=10&page=1');
        $page1Response->assertStatus(200);
        $this->assertEquals(15, $page1Response->json('data.count'));
        $this->assertEquals(1, $page1Response->json('data.page'));
        $this->assertEquals(10, $page1Response->json('data.page_size'));
        $this->assertCount(10, $page1Response->json('data.list'));

        // Test second page
        $page2Response = $this->getJson('/api/v1/payment/credit-cards?limit=10&page=2');
        $page2Response->assertStatus(200);
        $this->assertEquals(15, $page2Response->json('data.count'));
        $this->assertEquals(2, $page2Response->json('data.page'));
        $this->assertEquals(10, $page2Response->json('data.page_size'));
        $this->assertCount(5, $page2Response->json('data.list')); // Remaining 5 cards

        // Test third page (should be empty)
        $page3Response = $this->getJson('/api/v1/payment/credit-cards?limit=10&page=3');
        $page3Response->assertStatus(200);
        $this->assertEquals(15, $page3Response->json('data.count'));
        $this->assertEquals(3, $page3Response->json('data.page'));
        $this->assertEquals(10, $page3Response->json('data.page_size'));
        $this->assertCount(0, $page3Response->json('data.list'));
    }

    public function test_card_type_detection(): void
    {
        $testCases = [
            ['card_number' => '4111111111111111', 'expected_type' => 'Visa'],
            ['card_number' => '5555555555554444', 'expected_type' => 'MasterCard'],
            ['card_number' => '378282246310005', 'expected_type' => 'American Express'],
            ['card_number' => '6011111111111117', 'expected_type' => 'Discover'],
            ['card_number' => '1234567890123456', 'expected_type' => 'Unknown'],
        ];

        foreach ($testCases as $testCase) {
            $response = $this->postJson('/api/v1/payment/credit-cards', [
                'first_name' => 'Test',
                'last_name' => 'User',
                'phone' => '+1234567890',
                'card_number' => $testCase['card_number'],
                'security_code' => '123',
                'expiration_year' => '2025',
                'expiration_month' => '12',
                'zip' => '12345',
                'is_agree_to_receive' => true,
            ]);

            $response->assertStatus(200);
            $this->assertEquals($testCase['expected_type'], $response->json('data.type'));
        }
    }

    public function test_card_number_masking(): void
    {
        $response = $this->postJson('/api/v1/payment/credit-cards', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '+1234567890',
            'card_number' => '4111111111111111',
            'security_code' => '123',
            'expiration_year' => '2025',
            'expiration_month' => '12',
            'zip' => '12345',
            'is_agree_to_receive' => true,
        ]);

        $response->assertStatus(200);
        $this->assertEquals('****1111', $response->json('data.num')); // Should be masked

        // Verify in database
        $card = CreditCard::where('user_id', $this->user->id)->first();
        $this->assertEquals('****1111', $card->num);
    }

    public function test_multiple_users_isolation(): void
    {
        $user2 = User::factory()->create();
        
        // Create card for first user
        $response1 = $this->postJson('/api/v1/payment/credit-cards', [
            'first_name' => 'User1',
            'last_name' => 'Card',
            'phone' => '+1111111111',
            'card_number' => '4111111111111111',
            'security_code' => '123',
            'expiration_year' => '2025',
            'expiration_month' => '12',
            'zip' => '11111',
            'is_agree_to_receive' => true,
        ]);
        $response1->assertStatus(200);
        $cardId1 = $response1->json('data.id');

        // Switch to second user
        Sanctum::actingAs($user2);

        // Create card for second user
        $response2 = $this->postJson('/api/v1/payment/credit-cards', [
            'first_name' => 'User2',
            'last_name' => 'Card',
            'phone' => '+2222222222',
            'card_number' => '5555555555554444',
            'security_code' => '456',
            'expiration_year' => '2026',
            'expiration_month' => '06',
            'zip' => '22222',
            'is_agree_to_receive' => true,
        ]);
        $response2->assertStatus(200);
        $cardId2 = $response2->json('data.id');

        // Verify users can only see their own cards
        $listResponse = $this->getJson('/api/v1/payment/credit-cards');
        $listResponse->assertStatus(200);
        $this->assertEquals(1, $listResponse->json('data.count'));
        $this->assertEquals($cardId2, $listResponse->json('data.list.0.id'));

        // Try to access first user's card (should fail)
        $unauthorizedResponse = $this->putJson('/api/v1/payment/credit-cards', [
            'id' => $cardId1,
            'first_name' => 'Hacked',
            'last_name' => 'Name',
            'phone' => '+9999999999',
            'card_number' => '9999999999999999',
            'security_code' => '999',
            'expiration_year' => '2030',
            'expiration_month' => '01',
            'zip' => '99999',
            'is_agree_to_receive' => false,
        ]);
        $unauthorizedResponse->assertStatus(404); // Card not found for this user
    }
}
