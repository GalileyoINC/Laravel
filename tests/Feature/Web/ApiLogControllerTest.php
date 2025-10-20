<?php

declare(strict_types=1);

namespace Tests\Feature\Web;

use App\Http\Controllers\Web\ApiLogController;
use App\Models\System\ApiLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use App\Models\User\User;
use Tests\TestCase;

final class ApiLogControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->group(function () {
            Route::get('/test/api-logs', [ApiLogController::class, 'index']);
            Route::get('/test/api-logs/export', [ApiLogController::class, 'export']);
            Route::get('/test/api-logs/{apiLog}', [ApiLogController::class, 'show']);
            Route::delete('/test/api-logs/{apiLog}/delete-by-key', [ApiLogController::class, 'deleteByKey']);
        });
    }

    public function test_index_responds_ok(): void
    {
        $response = $this->get('/test/api-logs');
        $response->assertStatus(200);
    }

    public function test_show_responds_ok_with_existing_log(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $id = DB::table('api_log')->insertGetId([
            'key' => 'test-key',
            'value' => 'payload',
            'created_at' => now(),
        ]);
        $log = ApiLog::query()->findOrFail($id);

        $response = $this->get('/test/api-logs/'.$log->id);
        $response->assertStatus(200);
    }

    public function test_delete_by_key_deletes_all_with_same_key(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $id1 = DB::table('api_log')->insertGetId(['key' => 'same-key', 'value' => 'a', 'created_at' => now()]);
        $id2 = DB::table('api_log')->insertGetId(['key' => 'same-key', 'value' => 'b', 'created_at' => now()]);
        $id3 = DB::table('api_log')->insertGetId(['key' => 'other-key', 'value' => 'c', 'created_at' => now()]);
        $log1 = ApiLog::query()->findOrFail($id1);

        $response = $this->delete('/test/api-logs/'.$log1->id.'/delete-by-key');
        $response->assertStatus(302);

        $this->assertDatabaseMissing('api_log', ['id' => $id1]);
        $this->assertDatabaseMissing('api_log', ['id' => $id2]);
        $this->assertDatabaseHas('api_log', ['id' => $id3]);
    }

    public function test_export_streams_csv_download(): void
    {
        $response = $this->get('/test/api-logs/export');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type');
        $response->assertHeader('Content-Disposition');
    }
}
