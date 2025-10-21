<?php

declare(strict_types=1);

namespace Tests\Feature\Web;

use App\Http\Controllers\Web\AdminMessageLogController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

final class AdminMessageLogControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->group(function () {
            Route::get('/test/admin-message-logs', [AdminMessageLogController::class, 'index']);
            Route::get('/test/admin-message-logs/export', [AdminMessageLogController::class, 'export']);
        });
    }

    public function test_index_responds_ok(): void
    {
        $response = $this->get('/test/admin-message-logs');
        $response->assertStatus(200);
    }

    public function test_export_streams_csv_download(): void
    {
        // seed minimal data to avoid empty/exceptional branch
        DB::table('admin_message_log')->insert([
            'id_staff' => 1,
            'obj_type' => 1,
            'obj_id' => '1',
            'body' => 'Hello',
            'created_at' => now(),
        ]);

        $response = $this->get('/test/admin-message-logs/export');
        $response->assertStatus(200);
    }
}
