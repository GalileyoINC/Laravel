<?php

declare(strict_types=1);

namespace Tests\Feature\Web;

use App\Http\Controllers\Web\AdminMessageLogController;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $response = $this->get('/test/admin-message-logs/export');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type');
        $response->assertHeader('Content-Disposition');
    }
}
