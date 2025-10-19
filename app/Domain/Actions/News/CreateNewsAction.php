<?php

declare(strict_types=1);

namespace App\Domain\Actions\News;

use App\Models\Communication\SmsPool;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateNewsAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Get authenticated user
            $user = Auth::user();
            if (! $user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                ], 401);
            }

            // Use provided user_id if admin, otherwise use authenticated user
            $userId = $user->id;
            if (isset($data['user_id']) && $user->role === 1) {
                $userId = $data['user_id'];
            }

            // Create news item
            $news = SmsPool::create([
                'id_user' => $userId,
                'purpose' => 1, // Default purpose (1 = general/news)
                'body' => $data['content'] ?? '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Handle media files if provided
            $mediaFiles = [];

            // Check for FormData media files (media[0], media[1], etc.)
            foreach ($data as $key => $value) {
                if (str_starts_with((string) $key, 'media[') && str_ends_with((string) $key, ']')) {
                    if ($value instanceof \Illuminate\Http\UploadedFile) {
                        $mediaFiles[] = $value;
                    }
                }
            }

            // Also check for direct media array
            if (isset($data['media']) && is_array($data['media'])) {
                foreach ($data['media'] as $mediaFile) {
                    if ($mediaFile instanceof \Illuminate\Http\UploadedFile) {
                        $mediaFiles[] = $mediaFile;
                    }
                }
            }

            // Process all media files
            foreach ($mediaFiles as $mediaFile) {
                $path = $mediaFile->store('news-media', 'public');
                $filename = $mediaFile->getClientOriginalName();

                // Create media record using correct table structure
                DB::table('sms_pool_photo')->insert([
                    'id_sms_pool' => $news->id,
                    'folder_name' => 'news-media',
                    'web_name' => $filename,
                    'sizes' => json_encode([
                        [
                            'url' => $path,
                            'width' => null,
                            'height' => null,
                            'size' => 'original',
                        ],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'uuid' => \Illuminate\Support\Str::uuid(),
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'News created successfully',
                'data' => [
                    'id' => $news->id,
                    'body' => $news->body,
                    'purpose' => $news->purpose,
                    'created_at' => $news->created_at,
                ],
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create news: '.$e->getMessage(),
            ], 500);
        }
    }
}
