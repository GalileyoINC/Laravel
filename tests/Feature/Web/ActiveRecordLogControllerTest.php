<?php

declare(strict_types=1);

namespace Tests\Feature\Web;

use App\Http\Controllers\Web\ActiveRecordLogController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

final class ActiveRecordLogControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Register test-only routes using the real controller and container DI
        Route::middleware('web')->group(function () {
            Route::get('/test/active-record-logs', [ActiveRecordLogController::class, 'index']);
            Route::get('/test/active-record-logs/export', [ActiveRecordLogController::class, 'export']);
        });
    }

    public function test_index_responds_ok(): void
    {
        $response = $this->get('/test/active-record-logs');
        $response->assertStatus(200);
    }

    public function test_export_streams_csv_download(): void
    {
        // seed minimal data
        DB::table('active_record_log')->insert([
            'id_staff' => 1,
            'action_type' => 1,
            'model' => 'Test',
            'id_model' => '1',
            'field' => 'field',
            'changes' => json_encode(['old' => 'a', 'new' => 'b']),
            'created_at' => now(),
        ]);

        $response = $this->get('/test/active-record-logs/export');
        $response->assertStatus(200);
    }
}
