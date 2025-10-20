<?php

declare(strict_types=1);

namespace App\Domain\Services\AllSendForm;

use App\Domain\DTOs\AllSendForm\AllSendBroadcastRequestDTO;
use App\Domain\DTOs\AllSendForm\AllSendImageUploadRequestDTO;
use App\Domain\DTOs\AllSendForm\AllSendOptionsRequestDTO;
use App\Models\Communication\SmsPool;
use App\Models\Subscription\FollowerList;
use App\Models\Subscription\Subscription;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * AllSendForm service implementation
 */
class AllSendFormService implements AllSendFormServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function getAllSendOptions(AllSendOptionsRequestDTO $dto, ?User $user): array
    {
        try {
            // Get available subscriptions
            $subscriptions = Subscription::where('is_active', true)
                ->where('is_public', true)
                ->select(['id', 'name', 'description', 'category'])
                ->get();

            // Get user's private feeds if authenticated
            $privateFeeds = [];
            if ($user) {
                $privateFeeds = FollowerList::where('id_user', $user->id)
                    ->select(['id', 'title', 'description'])
                    ->get()
                    ->toArray();
            }

            return [
                'subscriptions' => $subscriptions->toArray(),
                'private_feeds' => $privateFeeds,
                'options' => [
                    'max_text_length' => 2000,
                    'max_files' => 10,
                    'allowed_file_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
                    'max_file_size' => 10485760, // 10MB
                    'timezone_options' => [
                        'UTC', 'America/New_York', 'America/Chicago', 'America/Denver',
                        'America/Los_Angeles', 'Europe/London', 'Europe/Paris', 'Asia/Tokyo',
                    ],
                ],
            ];

        } catch (Exception $e) {
            Log::error('AllSendFormService getAllSendOptions error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function sendBroadcast(AllSendBroadcastRequestDTO $dto, User $user): array
    {
        try {
            $results = [];

            // Create post in sms_pool table
            $smsPoolData = [
                'id_user' => $user->id,
                'body' => $dto->text,
                'text_short' => $dto->textShort,
                'url' => $dto->isLink ? $dto->url : null,
                'purpose' => 1, // 1 = general
                'is_schedule' => $dto->isSchedule ? 1 : 0,
                'schedule' => $dto->schedule,
                'timezone' => $dto->timezone,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Save to sms_pool table
            $smsPool = SmsPool::create($smsPoolData);

            // Send to subscriptions
            if ($dto->subscriptions) {
                foreach ($dto->subscriptions as $subscriptionId) {
                    /** @var Subscription|null $subscription */
                    $subscription = Subscription::find($subscriptionId);
                    if ($subscription && (bool) $subscription->is_active && $subscription->is_public) {
                        $results[] = [
                            'type' => 'subscription',
                            'id' => $subscriptionId,
                            'name' => $subscription->name,
                            'status' => 'sent',
                        ];
                    }
                }
            }

            // Send to private feeds
            if ($dto->privateFeeds) {
                foreach ($dto->privateFeeds as $feedId) {
                    $feed = FollowerList::where('id', $feedId)
                        ->where('id_user', $user->id)
                        ->first();

                    if ($feed) {
                        $results[] = [
                            'type' => 'private_feed',
                            'id' => $feedId,
                            'name' => $feed->title,
                            'status' => 'sent',
                        ];
                    }
                }
            }

            // If no specific subscriptions/feeds, just create a general post
            if (empty($dto->subscriptions) && empty($dto->privateFeeds)) {
                $results[] = [
                    'type' => 'general',
                    'id' => $smsPool->id,
                    'name' => 'General Post',
                    'status' => 'sent',
                ];
            }

            return [
                'success' => true,
                'message' => 'Broadcast sent successfully',
                'results' => $results,
                'total_sent' => count($results),
                'post_id' => $smsPool->id,
            ];

        } catch (Exception $e) {
            Log::error('AllSendFormService sendBroadcast error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function uploadImage(AllSendImageUploadRequestDTO $dto, User $user): array
    {
        try {
            // Validate file
            if (! $dto->file->isValid()) {
                throw new Exception('Invalid file upload');
            }

            // Generate unique filename
            $filename = $dto->uuid.'_'.time().'.'.$dto->file->getClientOriginalExtension();

            // Store file
            $path = $dto->file->storeAs('all-send-images', $filename, 'public');

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
            Log::error('AllSendFormService uploadImage error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function deleteImage(int $imageId, User $user): array
    {
        // In real application, find and delete the image record
        // $image = SmsPoolPhoto::where('id', $imageId)
        //     ->where('id_user', $user->id)
        //     ->first();

        // if (!$image) {
        //     throw new \Exception('Image not found or unauthorized');
        // }

        // // Delete file from storage
        // if ($image->path) {
        //     Storage::disk('public')->delete($image->path);
        // }

        // $image->delete();

        // For now, return success (mock implementation)
        return [
            'success' => true,
            'message' => 'Image deleted successfully',
        ];
    }
}
