<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\ContractLine\ExportUnpaidContractLinesToCsvAction;
use App\Domain\Actions\ContractLine\GetUnpaidContractLinesAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContractLine\Web\UnpaidContractLinesRequest;
use App\Models\Finance\Service;
use App\Models\User\UserPlan;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContractLineController extends Controller
{
    public function __construct(
        private readonly GetUnpaidContractLinesAction $getUnpaidContractLinesAction,
        private readonly ExportUnpaidContractLinesToCsvAction $exportUnpaidContractLinesToCsvAction,
    ) {}

    /**
     * Display Unpaid Contract Lines
     */
    public function unpaid(UnpaidContractLinesRequest $request): View
    {
        $filters = $request->validated();
        $contractLines = $this->getUnpaidContractLinesAction->execute($filters, 20);

        // Get dropdown data
        $services = Service::whereIn('type', [Service::TYPE_SUBSCRIBE, Service::TYPE_DEVICE_PLAN])
            ->pluck('name', 'id')
            ->toArray();
        $payIntervals = UserPlan::getPayIntervals();

        return ViewFacade::make('contract-line.unpaid', [
            'contractLines' => $contractLines,
            'services' => $services,
            'payIntervals' => $payIntervals,
            'expDateDays' => $filters['exp_date'] ?? 30,
            'title' => 'Users who are overdue for their next payment',
            'filters' => $filters,
        ]);
    }

    /**
     * Export Unpaid Contract Lines to CSV
     */
    public function exportUnpaid(UnpaidContractLinesRequest $request): StreamedResponse
    {
        $filters = $request->validated();
        $rows = $this->exportUnpaidContractLinesToCsvAction->execute($filters);

        $filename = 'unpaid_contract_lines_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $file = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
