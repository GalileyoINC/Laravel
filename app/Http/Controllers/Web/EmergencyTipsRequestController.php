<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\EmergencyTipsRequest\ExportEmergencyTipsRequestsToCsvAction;
use App\Domain\Actions\EmergencyTipsRequest\GetEmergencyTipsRequestListAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmergencyTipsRequest\Web\EmergencyTipsRequestIndexRequest;
use App\Models\User\EmergencyTipsRequest;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmergencyTipsRequestController extends Controller
{
    public function __construct(
        private readonly GetEmergencyTipsRequestListAction $getEmergencyTipsRequestListAction,
        private readonly ExportEmergencyTipsRequestsToCsvAction $exportEmergencyTipsRequestsToCsvAction,
    ) {}

    /**
     * Display Emergency Tips Requests
     */
    public function index(EmergencyTipsRequestIndexRequest $request): View
    {
        $filters = $request->validated();
        $emergencyTipsRequests = $this->getEmergencyTipsRequestListAction->execute($filters, 20);

        return ViewFacade::make('emergency-tips-request.index', [
            'emergencyTipsRequests' => $emergencyTipsRequests,
            'filters' => $filters,
        ]);
    }

    /**
     * Show Emergency Tips Request Details
     */
    public function show(EmergencyTipsRequest $emergencyTipsRequest): View
    {
        return ViewFacade::make('emergency-tips-request.show', [
            'emergencyTipsRequest' => $emergencyTipsRequest,
        ]);
    }

    /**
     * Export Emergency Tips Requests to CSV
     */
    public function export(EmergencyTipsRequestIndexRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $csvData = $this->exportEmergencyTipsRequestsToCsvAction->execute($filters);
        $filename = 'emergency_tips_requests_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
