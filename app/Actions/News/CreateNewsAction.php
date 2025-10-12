<?php

namespace App\Actions\News;

use App\Models\SmsPool;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateNewsAction
{
    public function execute(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            if (! $user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Create news item
            $news = SmsPool::create([
                'id_user' => $user->id,
                'purpose' => 'general', // Default purpose
                'title' => $data['content'] ?? '',
                'body' => $data['content'] ?? '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Handle media files if provided
            $mediaFiles = [];
            
            // Check for FormData media files (media[0], media[1], etc.)
            foreach ($data as $key => $value) {
                if (str_starts_with($key, 'media[') && str_ends_with($key, ']')) {
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
                            'size' => 'original'
                        ]
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
                    'title' => $news->title,
                    'body' => $news->body,
                    'created_at' => $news->created_at,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create news: '.$e->getMessage(),
            ], 500);
        }
    }
}
