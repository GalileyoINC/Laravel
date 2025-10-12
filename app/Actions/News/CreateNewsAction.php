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
            if (isset($data['media']) && is_array($data['media'])) {
                foreach ($data['media'] as $index => $mediaFile) {
                    if ($mediaFile instanceof \Illuminate\Http\UploadedFile) {
                        $path = $mediaFile->store('news-media', 'public');

                        // Create media record (assuming you have a media table)
                        // You might need to create a Media model and table
                        DB::table('sms_pool_photo')->insert([
                            'id_sms_pool' => $news->id,
                            'path' => $path,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
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
