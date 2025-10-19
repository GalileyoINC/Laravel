<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Artisan;
use DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFacade;

class MaintenanceController extends Controller
{
    /**
     * Display Maintenance Dashboard
     */
    public function index(): View
    {
        return ViewFacade::make('maintenance.index');
    }

    /**
     * Set Session Data
     */
    public function setSession(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'nullable|string',
        ]);
        $key = $request->get('key');
        $value = $request->get('value');

        if (empty($value)) {
            session()->forget($key);

            return response()->json(['success' => true, 'message' => 'Session key removed']);
        }
        session([$key => $value]);

        return response()->json(['success' => true, 'message' => 'Session key set']);
    }

    /**
     * Clear Cache
     */
    public function clearCache(): RedirectResponse
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return redirect()->back()
            ->with('success', 'Cache cleared successfully.');
    }

    /**
     * Clear Logs
     */
    public function clearLogs(): RedirectResponse
    {
        Artisan::call('log:clear');

        return redirect()->back()
            ->with('success', 'Logs cleared successfully.');
    }

    /**
     * Database Maintenance
     */
    public function databaseMaintenance(): RedirectResponse
    {
        Artisan::call('migrate:status');
        Artisan::call('db:seed --class=DatabaseSeeder');

        return redirect()->back()
            ->with('success', 'Database maintenance completed successfully.');
    }

    /**
     * System Information
     */
    public function systemInfo(): View
    {
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'server_os' => PHP_OS,
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
            'database_driver' => config('database.default'),
        ];

        return ViewFacade::make('maintenance.system-info', [
            'systemInfo' => $systemInfo,
        ]);
    }

    /**
     * Queue Status
     */
    public function queueStatus(): View
    {
        $queueInfo = [
            'driver' => config('queue.default'),
            'connection' => config('queue.connections.'.config('queue.default')),
            'failed_jobs_count' => DB::table('failed_jobs')->count(),
            'jobs_count' => DB::table('jobs')->count(),
        ];

        return ViewFacade::make('maintenance.queue-status', [
            'queueInfo' => $queueInfo,
        ]);
    }

    /**
     * Storage Status
     */
    public function storageStatus(): View
    {
        $storageInfo = [
            'disk' => config('filesystems.default'),
            'public_path' => public_path(),
            'storage_path' => storage_path(),
            'logs_path' => storage_path('logs'),
            'cache_path' => storage_path('framework/cache'),
            'sessions_path' => storage_path('framework/sessions'),
            'views_path' => storage_path('framework/views'),
        ];

        return ViewFacade::make('maintenance.storage-status', [
            'storageInfo' => $storageInfo,
        ]);
    }
}
