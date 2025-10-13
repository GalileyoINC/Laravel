<?php

declare(strict_types=1);

namespace Tests\Unit\Unit\Unit\Chat;

use App\DTOs\Chat\ChatListRequestDTO;
use App\DTOs\Chat\ChatMessagesRequestDTO;
use App\Models\Communication\Conversation;
use App\Models\Communication\ConversationMessage;
use App\Models\User\User;
use App\Services\Chat\ChatService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatServiceTest extends TestCase
{
    use RefreshDatabase;

    private ChatService $chatService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->chatService = new ChatService();
        // Ensure the database is migrated for each test
        $this->artisan('migrate');
    }

    /** @test */
    public function get_conversation_list_returns_user_conversations(): void
    {
        // Arrange
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();
        
        // Add user to conversation
        $conversation->users()->attach($user->id);

        $dto = new ChatListRequestDTO(
            limit: 10,
            offset: 0,
            filter: []
        );

        // Act
        $result = $this->chatService->getConversationList($dto, $user);

        // Assert
        $this->assertCount(1, $result);
        $this->assertInstanceOf(Conversation::class, $result->first());
    }

    /** @test */
    public function get_conversation_list_applies_limit_and_offset(): void
    {
        // Arrange
        $user = User::factory()->create();
        
        // Create multiple conversations
        for ($i = 0; $i < 5; $i++) {
            $conversation = Conversation::factory()->create();
            $conversation->users()->attach($user->id);
        }

        $dto = new ChatListRequestDTO(
            limit: 3,
            offset: 1,
            filter: []
        );

        // Act
        $result = $this->chatService->getConversationList($dto, $user);

        // Assert
        $this->assertCount(3, $result);
    }

    /** @test */
    public function get_conversation_list_returns_empty_when_no_conversations(): void
    {
        // Arrange
        $user = User::factory()->create();

        $dto = new ChatListRequestDTO(
            limit: 10,
            offset: 0,
            filter: []
        );

        // Act
        $result = $this->chatService->getConversationList($dto, $user);

        // Assert
        $this->assertCount(0, $result);
    }

    /** @test */
    public function get_conversation_list_only_returns_user_conversations(): void
    {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $conversation1 = Conversation::factory()->create();
        $conversation1->users()->attach($user1->id);
        
        $conversation2 = Conversation::factory()->create();
        $conversation2->users()->attach($user2->id);

        $dto = new ChatListRequestDTO(
            limit: 10,
            offset: 0,
            filter: []
        );

        // Act
        $result = $this->chatService->getConversationList($dto, $user1);

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals($conversation1->id, $result->first()->id);
    }

    /** @test */
    public function get_conversation_messages_returns_messages_for_conversation(): void
    {
        // Arrange
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();
        
        // Add user to conversation
        $conversation->users()->attach($user->id);

        // Create messages
        ConversationMessage::factory()->count(3)->create([
            'id_conversation' => $conversation->id,
            'id_user' => $user->id,
        ]);

        $dto = new ChatMessagesRequestDTO(
            conversationId: $conversation->id,
            limit: 10,
            offset: 0
        );

        // Act
        $result = $this->chatService->getConversationMessages($dto, $user);

        // Assert
        $this->assertCount(3, $result);
        $this->assertInstanceOf(ConversationMessage::class, $result->first());
    }

    /** @test */
    public function get_conversation_messages_applies_limit_and_offset(): void
    {
        // Arrange
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();
        
        // Add user to conversation
        $conversation->users()->attach($user->id);

        // Create messages
        ConversationMessage::factory()->count(5)->create([
            'id_conversation' => $conversation->id,
            'id_user' => $user->id,
        ]);

        $dto = new ChatMessagesRequestDTO(
            conversationId: $conversation->id,
            limit: 2,
            offset: 1
        );

        // Act
        $result = $this->chatService->getConversationMessages($dto, $user);

        // Assert
        $this->assertCount(2, $result);
    }

    /** @test */
    public function get_conversation_messages_throws_exception_for_non_existent_conversation(): void
    {
        // Arrange
        $user = User::factory()->create();

        $dto = new ChatMessagesRequestDTO(
            conversationId: 999,
            limit: 10,
            offset: 0
        );

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Conversation not found');
        
        $this->chatService->getConversationMessages($dto, $user);
    }

    /** @test */
    public function get_conversation_messages_throws_exception_for_unauthorized_conversation(): void
    {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conversation = Conversation::factory()->create();
        
        // Add only user2 to conversation
        $conversation->users()->attach($user2->id);

        $dto = new ChatMessagesRequestDTO(
            conversationId: $conversation->id,
            limit: 10,
            offset: 0
        );

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Conversation not found');
        
        $this->chatService->getConversationMessages($dto, $user1);
    }

    /** @test */
    public function get_conversation_view_returns_conversation_with_relations(): void
    {
        // Arrange
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();
        
        // Add user to conversation
        $conversation->users()->attach($user->id);

        // Create messages
        ConversationMessage::factory()->count(2)->create([
            'id_conversation' => $conversation->id,
            'id_user' => $user->id,
        ]);

        // Act
        $result = $this->chatService->getConversationView($conversation->id, $user);

        // Assert
        $this->assertInstanceOf(Conversation::class, $result);
        $this->assertEquals($conversation->id, $result->id);
        $this->assertTrue($result->relationLoaded('users'));
        $this->assertTrue($result->relationLoaded('conversation_messages'));
    }

    /** @test */
    public function get_conversation_view_throws_exception_for_non_existent_conversation(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Conversation not found');
        
        $this->chatService->getConversationView(999, $user);
    }

    /** @test */
    public function get_conversation_view_throws_exception_for_unauthorized_conversation(): void
    {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conversation = Conversation::factory()->create();
        
        // Add only user2 to conversation
        $conversation->users()->attach($user2->id);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Conversation not found');
        
        $this->chatService->getConversationView($conversation->id, $user1);
    }

    /** @test */
    public function upload_files_returns_not_implemented_message(): void
    {
        // Arrange
        $user = User::factory()->create();
        $files = ['file1.jpg', 'file2.pdf'];

        // Act
        $result = $this->chatService->uploadFiles($files, 1, $user);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals('File upload not implemented yet', $result['message']);
    }

    /** @test */
    public function create_group_chat_returns_not_implemented_message(): void
    {
        // Arrange
        $user = User::factory()->create();
        $data = ['name' => 'Test Group', 'members' => [1, 2, 3]];

        // Act
        $result = $this->chatService->createGroupChat($data, $user);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals('Group chat creation not implemented yet', $result['message']);
    }

    /** @test */
    public function get_friend_chat_returns_not_implemented_message(): void
    {
        // Arrange
        $user = User::factory()->create();
        $friendId = 123;

        // Act
        $result = $this->chatService->getFriendChat($friendId, $user);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals('Friend chat not implemented yet', $result['message']);
    }
}