<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Finance\ContractLine;
use App\Models\Finance\Service;
use App\Models\User\UserPlan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class ContractLineController extends Controller
{
    /**
     * Display Unpaid Contract Lines
     */
    public function unpaid(Request $request): View
    {
        $query = ContractLine::with(['user', 'service'])
            ->whereHas('userPlan', function ($q) {
                $q->where('end_at', '<', now());
            });

        // Filter by end_at date (days)
        $expDateDays = $request->get('exp_date', 30);
        if ($expDateDays) {
            $query->where('end_at', '>=', now()->subDays($expDateDays));
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by service
        if ($request->filled('id_service')) {
            $query->where('id_service', $request->get('id_service'));
        }

        // Filter by pay interval
        if ($request->filled('pay_interval')) {
            $query->where('pay_interval', $request->get('pay_interval'));
        }

        $contractLines = $query->orderBy('end_at', 'asc')->paginate(20);

        // Get dropdown data
        $services = Service::whereIn('type', [Service::TYPE_SUBSCRIBE, Service::TYPE_DEVICE_PLAN])
            ->pluck('name', 'id')
            ->toArray();
        $payIntervals = UserPlan::getPayIntervals();

        return ViewFacade::make('contract-line.unpaid', [
            'contractLines' => $contractLines,
            'services' => $services,
            'payIntervals' => $payIntervals,
            'expDateDays' => $expDateDays,
            'title' => 'Users who are overdue for their next payment',
            'filters' => $request->only(['search', 'id_service', 'pay_interval', 'exp_date']),
        ]);
    }

    /**
     * Export Unpaid Contract Lines to CSV
     */
    public function exportUnpaid(Request $request): Response
    {
            $query = ContractLine::with(['user', 'service'])
                ->whereHas('userPlan', function ($q) {
                    $q->where('end_at', '<', now());
                });

            // Apply same filters as unpaid
            $expDateDays = $request->get('exp_date', 30);
            if ($expDateDays) {
                $query->where('end_at', '>=', now()->subDays($expDateDays));
            }

            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('id_service')) {
                $query->where('id_service', $request->get('id_service'));
            }

            if ($request->filled('pay_interval')) {
                $query->where('pay_interval', $request->get('pay_interval'));
            }

            $contractLines = $query->orderBy('end_at', 'asc')->get();

            $csvData = [];
            $csvData[] = ['User ID', 'First Name', 'Last Name', 'Email', 'Service', 'Pay Interval', 'End At'];

            foreach ($contractLines as $contractLine) {
                $csvData[] = [
                    $contractLine->id_user,
                    $contractLine->first_name,
                    $contractLine->last_name,
                    $contractLine->email,
                    $contractLine->service_name,
                    $contractLine->pay_interval ? ($contractLine->pay_interval == 1 ? 'Monthly' : ($contractLine->pay_interval == 12 ? 'Annual' : (string)$contractLine->pay_interval)) : '',
                    $contractLine->end_at ? $contractLine->end_at->format('Y-m-d') : '',
                ];
            }

            $filename = 'unpaid_contract_lines_'.now()->format('Y-m-d_H-i-s').'.csv';

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
