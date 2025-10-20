<?php

declare(strict_types=1);

namespace Tests\Feature\Web;

use App\Http\Controllers\Web\ActiveRecordLogController;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $response = $this->get('/test/active-record-logs/export');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type');
        $response->assertHeader('Content-Disposition');
    }
}
