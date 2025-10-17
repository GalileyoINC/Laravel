<?php

declare(strict_types=1);

namespace App\Domain\Services\PublicFeed;

use App\Domain\DTOs\PublicFeed\PublicFeedImageUploadRequestDTO;
use App\Domain\DTOs\PublicFeed\PublicFeedOptionsRequestDTO;
use App\Domain\DTOs\PublicFeed\PublicFeedPublishRequestDTO;
use App\Models\Subscription\Subscription;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * PublicFeed service implementation
 */
class PublicFeedService implements PublicFeedServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPublicFeedOptions(PublicFeedOptionsRequestDTO $dto, ?User $user): array
    {
        try {
            // Get active public subscriptions
            $subscriptions = Subscription::where('is_active', true)
                ->where('is_public', true)
                ->select(['id', 'name', 'description'])
                ->get();

            return [
                'subscriptions' => $subscriptions->toArray(),
                'options' => [
                    'max_text_length' => 1000,
                    'max_files' => 5,
                    'allowed_file_types' => ['jpg', 'jpeg', 'png', 'gif'],
                    'max_file_size' => 5242880, // 5MB
                ],
            ];

        } catch (Exception $e) {
            Log::error('PublicFeedService getPublicFeedOptions error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function publishToPublicFeeds(PublicFeedPublishRequestDTO $dto, User $user): array
    {
        try {
            $results = [];

            // Send to each subscription
            foreach ($dto->subscriptions as $subscriptionId) {
                $subscription = Subscription::where('id', $subscriptionId)
                    ->where('is_active', true)
                    ->where('is_public', true)
                    ->first();

                if (! $subscription) {
                    throw new Exception("Invalid subscription: {$subscriptionId}");
                }

                // Create SMS/Message record (simplified)
                $messageData = [
                    'uuid' => $dto->uuid,
                    'text' => $dto->text,
                    'text_short' => $dto->textShort,
                    'url' => $dto->isLink ? $dto->url : null,
                    'id_subscription' => $subscriptionId,
                    'id_user' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // In real application, save to database
                // $sms = Sms::create($messageData);

                $results[] = [
                    'subscription_id' => $subscriptionId,
                    'subscription_name' => $subscription->name,
                    'status' => 'sent',
                    'message_id' => 'mock_message_'.time(),
                ];
            }

            return [
                'success' => true,
                'message' => 'Published to public feeds successfully',
                'results' => $results,
            ];

        } catch (Exception $e) {
            Log::error('PublicFeedService publishToPublicFeeds error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function uploadImage(PublicFeedImageUploadRequestDTO $dto, User $user): array
    {
        try {
            // Validate file
            if (! $dto->file->isValid()) {
                throw new Exception('Invalid file upload');
            }

            // Generate unique filename
            $filename = $dto->uuid.'_'.time().'.'.$dto->file->getClientOriginalExtension();

            // Store file
            $path = $dto->file->storeAs('public-feeds', $filename, 'public');

            // Create file record (simplified)
            $fileData = [
                'id' => 'mock_file_'.time(),
                'uuid' => $dto->uuid,
                'filename' => $filename,
                'path' => $path,
                'size' => $dto->file->getSize(),
                'mime_type' => $dto->file->getMimeType(),
                'id_user' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            return [
                'id_image' => $fileData['id'],
                'success' => true,
                'file' => $fileData,
            ];

        } catch (Exception $e) {
            Log::error('PublicFeedService uploadImage error: '.$e->getMessage());
            throw $e;
        }
    }
}
